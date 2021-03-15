<?php

namespace App\Driven\Http;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;

final class Authorizer implements TransactionAuthorizer
{
    private ClientInterface $client;
    
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function authorize(): void
    {
        $request = new Request('GET', 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
        
        $response = $this->client->sendRequest($request);
        $data = json_decode($response->getBody()->getContents(), true);
    }
}
