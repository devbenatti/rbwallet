<?php

namespace App\Command\Transaction;

use App\Driver\WebApi\Validator\Validation;

final class TransactionValidation implements Validation
{
    public function getSpecification(): array
    {
        return [
            'type' => 'object',
            'required' => ['value', 'payer', 'payee'],
            'properties' => [
                'value' => [
                    'type' => 'number',
                    'minimum' => 0.01
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
