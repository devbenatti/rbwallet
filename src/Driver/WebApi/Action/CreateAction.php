<?php

namespace App\Driver\WebApi\Action;

use App\Command\CommandHandler;
use App\Command\Create\Create;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class CreateAction implements Action
{
    /**
     * @var CommandHandler
     */
    private CommandHandler $handler;

    public function __construct(CommandHandler $handler)
    {
        $this->handler = $handler;
    }
    
    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        $command = new Create(
            $data['name'],
            $data['document'],
            $data['password'],
            $data['email']
        );
        
        $this->handler->handle($command);
        
        return $response->withStatus(201);
    }
}
