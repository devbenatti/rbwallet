<?php

namespace Tests\Driver\API\Middleware;

use App\Driven\Database\DAO\PersonDAO;
use App\Driver\API\Middleware\AuthorizationTransaction;
use App\Model\Wallet\Person;
use App\Model\VO\DocumentType;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;

class TestTransactionAuthentication extends TestCase
{
    public function testSuccess()
    {
        $request = $this->createRequest();
        
        $personData = $this->getPersonData();
        $person = Person::build($personData);
        
        $personDAO = $this->createMock(PersonDAO::class);
        
        $personDAO->method('getById')
            ->withAnyParameters()
            ->willReturn($person);
        
        $handler = $this->createMock(RequestHandlerInterface::class);
        
        $middleware = new AuthorizationTransaction($personDAO);
        
        $middleware->process($request, $handler);

        static::expectNotToPerformAssertions();
    }

    public function testPayerMerchantShouldThrowForbbidenException()
    {
        $this->expectException(HttpForbiddenException::class);
        $this->expectExceptionMessage('Merchants can only receive transaction');

        $request = $this->createRequest();
        
        $personData = $this->getPersonData([
            'document' => [
                'type' => DocumentType::CNPJ,
                'identifier' => '04954410974545'
            ]
        ]);
        
        $person = Person::build($personData);

        $personDAO = $this->createMock(PersonDAO::class);

        $personDAO->expects(static::exactly(1))
            ->method('getById')
            ->with(1)
            ->willReturn($person);

        $handler = $this->createMock(RequestHandlerInterface::class);

        $middleware = new AuthorizationTransaction($personDAO);

        $middleware->process($request, $handler);
    }
    
    public function testInvalidPayerShouldThrowBadRequestException()
    {
        $this->expectException(HttpBadRequestException::class);
        $this->expectExceptionMessage('Invalid payer');
        
        $request = $this->createRequest();
        
        $personDAO = $this->createMock(PersonDAO::class);

        $personDAO->expects(static::exactly(1))
            ->method('getById')
            ->with(1)
            ->willReturn(null);

        $handler = $this->createMock(RequestHandlerInterface::class);

        $middleware = new AuthorizationTransaction($personDAO);

        $middleware->process($request, $handler);
    }

    public function testInvalidPayeeShouldThrowBadRequestException()
    {
        $this->expectException(HttpBadRequestException::class);
        $this->expectExceptionMessage('Invalid payee');

        $request = $this->createRequest();
        
        $personData = $this->getPersonData();
        $person = Person::build($personData);

        $personDAO = $this->createMock(PersonDAO::class);

        $personDAO->expects(static::exactly(2))
            ->method('getById')
            ->withConsecutive([1], [2])
            ->willReturnOnConsecutiveCalls($person, null);
        

        $handler = $this->createMock(RequestHandlerInterface::class);

        $middleware = new AuthorizationTransaction($personDAO);

        $middleware->process($request, $handler);
    }
    
    private function createRequest(): ServerRequestInterface
    {
        $factory = new Psr17Factory();
        $request = $factory->createServerRequest('GET', '/');
        return $request->withParsedBody([
            'value' => 100.00,
            'payer' => 1,
            'payee' => 2
        ]);
    }

    /**
     * @param array $data
     * @return array
     */
    private function getPersonData(array $data = []): array
    {
        return array_filter(
            array_merge([
                'id' => 1,
                'document' => [
                    'type' => DocumentType::CPF,
                    'identifier' => '05719027540'
                ],
                'email' => 'xablau@gmail.com',
                'name' => 'Xablau testador'
            ], $data)
        );
    }
}
