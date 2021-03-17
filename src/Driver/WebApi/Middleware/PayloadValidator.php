<?php

namespace App\Driver\WebApi\Middleware;

use App\Command\Transaction\TransactionValidation;
use App\Driver\WebApi\Validator\ValidationData;
use App\Driver\WebApi\Validator\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;

final class PayloadValidator  implements MiddlewareInterface
{
    private Validator $validator;
    
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data =  new ValidationData($request->getParsedBody());
        $validation = new TransactionValidation();
        
        $result = $this->validator->validate($validation, $data);
        
        if(!$result->isValid()) {
            throw new HttpBadRequestException($request, json_encode($result->getErrors()));
        }
        
        return $handler->handle($request);
    }
}
