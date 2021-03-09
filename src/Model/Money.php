<?php

namespace App\Model;

final class Money
{
    use Immutability;
    
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
        
        $sum = $this->amount->add($toAdd->getAmount());
        
        return new static($sum, $this->currency);
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

        $sum = $this->amount->sub($toSub->getAmount());
        
        return new static($sum, $this->currency);
    }

    /**
     * @param array $data
     * @return Money
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

