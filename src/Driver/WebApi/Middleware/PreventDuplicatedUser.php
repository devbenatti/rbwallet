<?php

namespace App\Driver\WebApi\Middleware;

use App\Driven\Database\DAO\PersonDAO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;

final class PreventDuplicatedUser implements MiddlewareInterface
{

    /**
     * @var PersonDAO
     */
    private PersonDAO $personDAO;

    public function __construct(PersonDAO $personDAO)
    {
        $this->personDAO = $personDAO;
    }
    
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $data = $request->getParsedBody();
        
        $result = $this->personDAO->getByEmailOrDocument($data['email'], $data['document']);
        
        if($result) {
            throw new HttpBadRequestException($request, 'Duplicated email or document');
        }
        
        return $handler->handle($request);
    }
}
