<?php

namespace Tests\Command\Transaction;

use App\Command\Transfer\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function testSucess()
    {
        $test = new Transaction();
        
        static::assertTrue(true);
        
    }
}
