<?php

namespace App\Model\VO;

use App\Model\ImmutableCapabilities;
use ReflectionException;

final class Money
{
    use ImmutableCapabilities;
    
    private  Currency $currency;
    
    private Decimal $amount;

    /**
     * PositiveDecimal constructor.
     * @param Decimal $amount
     * @param Currency $currency
     */
    public function __construct(Decimal $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @param Money $toAdd
     * @return $this
     */
    public function add(Money $toAdd): Money
    {
        if ($toAdd->amount === 0) {
            return $this;
        }
        
        return new static($this->amount->add($toAdd->getAmount()), $this->currency);
    }

    /**
     * @param Money $toSub
     * @return $this
     */
    public function sub(Money $toSub): Money
    {
        if ($toSub->amount === 0) {
            return $this;
        }
        
        return new static($this->amount->sub($toSub->getAmount()), $this->currency);
    }

    /**
     * @param array $data
     * @return Money
     * @throws ReflectionException
     */
    public static function build(array $data): Money
    {
        $amount = new Decimal($data['amount']);
        $currency = new Currency($data['currency']);
        
        return new static($amount, $currency);
    }

    /**
     * @return Decimal
     */
    public function getAmount(): Decimal
    {
        return $this->amount;
    }
}

