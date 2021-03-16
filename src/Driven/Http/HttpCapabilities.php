<?php

namespace App\Driven\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

trait HttpCapabilities
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }
    
    private function getParsedBody(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}
