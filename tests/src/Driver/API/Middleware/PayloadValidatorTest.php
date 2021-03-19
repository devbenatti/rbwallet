<?php

namespace Tests\Driver\API\Middleware;

use App\Driver\WebApi\Middleware\PayloadValidator;
use App\Driver\WebApi\Validator\JsonSchemaValidator;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;

class PayloadValidatorTest extends TestCase
{
    public function testSuccess()
    {
        $request = $this->createRequest('POST', '/create');
        
        $validator =  new JsonSchemaValidator();

        $middleware = new PayloadValidator($validator);
        
        $handler = $this->createMock(RequestHandlerInterface::class);

        $middleware->process($request, $handler);

        static::expectNotToPerformAssertions();
    }

    /**
     * @param $data
     * @dataProvider dataProvider
     * @throws HttpBadRequestException
     */
    public function testInvalidDataShouldThrowException($data)
    {
        $request = $this->createRequest('POST', '/create', $data);

        $validator =  new JsonSchemaValidator();

        $middleware = new PayloadValidator($validator);

        $handler = $this->createMock(RequestHandlerInterface::class);

        $this->expectException(HttpBadRequestException::class);
        
        $middleware->process($request, $handler);
    }
    
    public function dataProvider(): array
    {
        return [
            [
               [
                   'email' => ''
               ] 
            ],
            [
               [
                   'name' => '' 
               ] 
            ],
            [
                [
                    'password' => ''
                ]
            ],
            [
                [
                    'document' => ''
                ]
            ],
            [
                [
                    'email' => 1111
                ]
            ]
        ];
    }

    private function createRequest(string $method, string $path, array $body = []): ServerRequestInterface
    {
        $factory = new Psr17Factory();
        $request = $factory->createServerRequest($method, $path);
        $body = array_filter(
            array_merge([
                'email' => 'xablau@gmail.com',
                'name' => 'Xablau Testador',
                'password' => '123',
                'document' => '11181756039'
            ], $body)
        );
        return $request->withParsedBody($body);
    }
}
