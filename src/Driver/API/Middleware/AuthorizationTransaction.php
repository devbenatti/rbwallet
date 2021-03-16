<?php

namespace App\Driver\API\Middleware;

use App\Driven\Database\DAO\PersonDAO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpForbiddenException;

class AuthorizationTransaction implements MiddlewareInterface
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
        $parsedBody = $request->getParsedBody();
        
        $payer = $this->personDAO->getById($parsedBody['payer']);
        
        if (!$payer) {
            throw new HttpBadRequestException($request, 'Invalid payer');
        }
        
        if ($payer->isMerchant()) {
            throw new HttpForbiddenException($request, 'Merchants can only receive transaction');
        }

        $payee = $this->personDAO->getById($parsedBody['payee']);
        
        if (!$payee) {
            throw new HttpBadRequestException($request, 'Invalid payee');
        }
        
        return $handler->handle($request);
    }
}
