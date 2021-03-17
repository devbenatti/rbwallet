<?php

namespace App\Driven\Http;

use App\Model\Transaction;

interface TransactionAuthorizer
{
    /**
     * @param Transaction $transaction
     * @return void
     * @throws TransactionUnauthorizedException
     */
    public function authorize(Transaction $transaction): void;
}
