<?php

namespace Tests\Driver\API\Middleware;

use App\Driven\Database\DAO\PersonDAO;
use App\Driver\WebApi\Middleware\PreventDuplicatedUser;
use App\Model\Person;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;

class PreventDuplicatedUserTest extends TestCase
{
    public function testSuccess()
    {
        $request = $this->createRequest();

        $personData = $this->getPersonData();
        
        $personDAO = $this->createMock(PersonDAO::class);

        $personDAO->expects(static::once())
            ->method('getByEmailOrDocument')
            ->withConsecutive([$personData['email'], $personData['document']])
            ->willReturn(null);

        $handler = $this->createMock(RequestHandlerInterface::class);

        $middleware = new PreventDuplicatedUser($personDAO);

        $middleware->process($request, $handler);
    }
    
    public function testDuplicatedEmailOrDocumentShouldThrowException()
    {
        $request = $this->createRequest();

        $personData = $this->getPersonData();
        $person = Person::build($personData);

        $personDAO = $this->createMock(PersonDAO::class);
        $personDAO->expects(static::once())
            ->method('getByEmailOrDocument')
            ->withConsecutive([$personData['email'], $personData['document']])
            ->willReturn($person);

        $handler = $this->createMock(RequestHandlerInterface::class);

        $middleware = new PreventDuplicatedUser($personDAO);

        $this->expectException(HttpBadRequestException::class);
        $this->expectExceptionMessage('Duplicated email or document');
        $middleware->process($request, $handler);    
    }
    
    
    private function createRequest(): ServerRequestInterface
    {
        $factory = new Psr17Factory();
        $request = $factory->createServerRequest('POST', '/create');
        return $request->withParsedBody([
            'email' => 'xablau@gmail.com',
            'name' => 'Xablau Testador',
            'password' => '123',
            'document' => '11181756039'
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
                'document' =>  '11181756039',
                'email' => 'xablau@gmail.com',
                'name' => 'Xablau testador'
            ], $data)
        );
    }
}
