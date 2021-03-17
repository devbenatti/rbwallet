<?php

namespace App\Driven\Http;

use App\Model\Transaction;

interface TransactionNotifier
{
    public function notify(Transaction $transaction): void;
}
