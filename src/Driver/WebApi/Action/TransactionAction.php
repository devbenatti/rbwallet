<?php

namespace App\Driver\WebApi\Action;

use App\Command\CommandHandler;
use App\Command\Transaction\Transaction;
use App\Driven\Uuid\UuidGenerator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class TransactionAction implements Action
{
    
    private CommandHandler $handler;
    
    private UuidGenerator $uuidAdapter;

    /**
     * TransactionAction constructor.
     * @param CommandHandler $handler
     * @param UuidGenerator $uuidAdapter
     */
    public function __construct(CommandHandler $handler, UuidGenerator $uuidAdapter)
    {
        $this->handler = $handler;
        $this->uuidAdapter = $uuidAdapter;
    }
    
    public function __invoke(Request $request, Response $response): Response
    {
        $parsedBody = $request->getParsedBody();
        
        $code =  $this->uuidAdapter->generate()->getValue();
        
        $command = new Transaction(
            $code,
            $parsedBody['value'],
            $parsedBody['payer'],
            $parsedBody['payee']
        );
        
        $this->handler->handle($command);
        
        $payload = json_encode(['code' => strtoupper($code)], JSON_PRETTY_PRINT);
        $response->getBody()->write($payload);
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }
}
