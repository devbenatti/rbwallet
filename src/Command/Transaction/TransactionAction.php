<?php

namespace App\Command\Transaction;

use Doctrine\DBAL\Connection;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class TransactionAction
{
    
    private Connection $database;
    
    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $payload = json_encode(['hello' => 'World'], JSON_PRETTY_PRINT);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
