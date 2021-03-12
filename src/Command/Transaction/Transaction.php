<?php

namespace App\Command\Transaction;

use App\Command\Command;
use App\Model\ImmutableCapabilities;

final class Transaction implements Command
{
    use ImmutableCapabilities;
    
    private float $amount;
    
    private int $payer;
    
    private int $payee;
    
    public function __construct(float $amount, int $payer, int $payee)
    {
        $this->amount = $amount;
        $this->payer = $payer;
        $this->payee = $payee;
    }
    
    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getPayer(): int
    {
        return $this->payer;
    }

    /**
     * @param int $payer
     */
    public function setPayer(int $payer): void
    {
        $this->payer = $payer;
    }

    /**
     * @return int
     */
    public function getPayee(): int
    {
        return $this->payee;
    }

    /**
     * @param int $payee
     */
    public function setPayee(int $payee): void
    {
        $this->payee = $payee;
    }
}
