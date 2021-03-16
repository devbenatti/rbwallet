<?php

namespace App\Driven\Http;

interface TransactionAuthorizer
{
    /**
     * @return void
     * @throws TransactionUnauthorizedException
     */
    public function authorize(): void;
}
