<?php

namespace App\Command\Transaction;

use App\Command\Command;
use App\Model\ImmutableCapabilities;

final class Transaction implements Command
{
    use ImmutableCapabilities;
    
    private float $amount;
    
    private int $payerId;
    
    private int $payeeId;
    
    public function __construct(float $amount, int $payer, int $payee)
    {
        $this->amount = $amount;
        $this->payerId = $payer;
        $this->payeeId = $payee;
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
    public function getPayerId(): int
    {
        return $this->payerId;
    }

    /**
     * @param int $payerId
     */
    public function setPayerId(int $payerId): void
    {
        $this->payerId = $payerId;
    }

    /**
     * @return int
     */
    public function getPayeeId(): int
    {
        return $this->payeeId;
    }

    /**
     * @param int $payeeId
     */
    public function setPayeeId(int $payeeId): void
    {
        $this->payeeId = $payeeId;
    }
}
