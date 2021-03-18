<?php

namespace App\Command\Create;

use App\Driver\WebApi\Validator\Validation;

final class CreateValidation implements Validation
{

    public function getSpecification(): array
    {
        return [
            'type' => 'object',
            'required' => ['name', 'email', 'password', 'document'],
            'properties' => [
                'name' => [
                    'type' => 'string',
                ],
                'email' => [
                    'type' => 'string',
                ],
                'document' => [
                    'type' => 'string',
                    'maxLength' => 14,
                    "minLength" => 11
                ],
                'password' => [
                    'type' => 'string',
                    'minLength' => 3,
                ]
            ]
        ];
    }
}
