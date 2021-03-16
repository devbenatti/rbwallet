<?php

namespace App\Model\VO;

use App\Model\ImmutableCapabilities;
use ReflectionException;

final class Money
{
    use ImmutableCapabilities;
    
    private  Currency $currency;
    
    private Decimal $value;

    /**
     * PositiveDecimal constructor.
     * @param Decimal $value
     * @param Currency $currency
     */
    public function __construct(Decimal $value, Currency $currency)
    {
        $this->value = $value;
        $this->currency = $currency;
    }

    /**
     * @param Money $toAdd
     * @return $this
     */
    public function add(Money $toAdd): Money
    {
        if ($toAdd->value === 0) {
            return $this;
        }
        
        return new static($this->value->add($toAdd->getValue()), $this->currency);
    }

    /**
     * @param Money $toSub
     * @return $this
     */
    public function sub(Money $toSub): Money
    {
        if ($toSub->value === 0) {
            return $this;
        }
        
        return new static($this->value->sub($toSub->getValue()), $this->currency);
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
    public function getValue(): Decimal
    {
        return $this->value;
    }
}

