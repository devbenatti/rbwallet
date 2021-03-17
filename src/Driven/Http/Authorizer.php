<?php

namespace App\Driven\Http;

use App\Model\Transaction;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;

final class Authorizer implements TransactionAuthorizer
{
    use HttpCapabilities;

    /**
     * @var string
     */
    private const AUTHORIZED = 'Autorizado';

    /**
     * @param Transaction $transaction
     * @throws ClientExceptionInterface
     * @throws TransactionUnauthorizedException
     */
    public function authorize(Transaction $transaction): void
    {
        $request = new Request(
            'POST',
            'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6',
            [
                'content-type' => 'application/json'
            ],
            json_encode($transaction->toArray())
        );
        
        $response = $this->client->sendRequest($request);
        
        $data = $this->getParsedBody($response);
        
        if (empty($data['message']) || $data['message'] !== self::AUTHORIZED) {
            throw new TransactionUnauthorizedException();
        }
    }
}
