<?php

namespace App\Driver\API\Action;

use App\Command\CommandHandler;
use App\Command\Transaction\Transaction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class TransactionAction implements Action
{
    
    private CommandHandler $handler;
    
    /**
     * TransactionAction constructor.
     * @param CommandHandler $handler
     */
    public function __construct(CommandHandler $handler)
    {
        $this->handler = $handler;
    }
    
    public function __invoke(Request $request, Response $response): Response
    {
        $parsedBody = $request->getParsedBody();
        
        $command = new Transaction( $parsedBody['value'],$parsedBody['payer'], $parsedBody['payee']);
        
        $this->handler->handle($command);
        
        $payload = json_encode(['hello' => 'World'], JSON_PRETTY_PRINT);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
