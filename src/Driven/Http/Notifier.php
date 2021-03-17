<?php

namespace App\Driven\Http;

use App\Model\Transaction;
use GuzzleHttp\Psr7\Request;

final class Notifier implements TransactionNotifier
{
    use HttpCapabilities;

    /**
     * @var string
     */
    private const NOTIFIED = 'Enviado';
    
    public function notify(Transaction $transaction): void
    {
        $request = new Request(
            'POST',
            'https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04',
            [
                'content-type' => 'application/json'
            ],
            json_encode($transaction->toArray())
        );

        $response = $this->client->sendRequest($request);

        $data = $this->getParsedBody($response);

        if (empty($data['message']) || $data['message'] !== self::NOTIFIED) {
            throw new NotifierUnavailableException();
        }
    }
}
