<?php

namespace App\Driven\Http;

interface TransactionAuthorizer
{
    public function authorize(): void;
}
