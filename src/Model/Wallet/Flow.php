<?php

namespace App\Model\Wallet;

use App\Model\VO\Money;

final class Flow
{

    /**
     * @var int
     */
    const INFLOW = 1;

    /**
     * @var int
     */
    const OUTFLOW = 2;
    
    private Money $value;
    
    private string $description;
    
    private int $type;
    
    private function __construct(int $type, Money $value, string $description)
    {
        $this->type = $type;
        $this->value = $value;
        $this->description = $description;
    }
    
    public static function buildCashInflow(Transaction $transaction): Flow
    {
        return new static(static::INFLOW, $transaction->getAmount(), 'Money sent');
    }

    public static function buildCashOutflow(Transaction $transaction): Flow
    {
        return new static(static::OUTFLOW, $transaction->getAmount(), 'Money send');
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return Money
     */
    public function getValue(): Money
    {
        return $this->value;
    }
}
