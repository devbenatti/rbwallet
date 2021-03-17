<?php

namespace App\Command\Transaction;

use App\Driver\WebApi\Validator\Validation;
use App\Model\VO\Currency;

final class TransactionValidation implements Validation
{
    public function getSpecification(): array
    {
        return [
            'type' => 'object',
            'required' => ['amount', 'payer', 'payee', 'currency'],
            'properties' => [
                'amount' => [
                    'type' => 'number',
                    'minimum' => 0.01
                ],
                'currency' => [
                    'type' => [
                        'enum' => Currency::getConstants()
                    ]
                ],
                'payer' => [
                    'type' => 'number',
                    'minimum' => 1,
                ],
                'payee' => [
                    'type' => 'number',
                    'minimum' => 1,
                ]
            ]
        ];
    }
}
