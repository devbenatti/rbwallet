<?php

namespace App\DTO;

use App\Model\ImmutableCapabilities;
use App\Model\VO\Currency;
use App\Model\VO\Money;
use App\Model\VO\PositiveInt;
use App\Model\VO\TransactionStatus;
use App\Model\VO\TransactionType;
use App\Model\VO\Uuid;
use ReflectionException;

final class TransactionDTO
{
    use ImmutableCapabilities;
    
    private Uuid $code;
    
    private TransactionType $type;
    
    private Money $amount;
    
    private TransactionStatus $status;
    
    private PositiveInt $origin;
    
    private PositiveInt $destination;
    
    public function __construct(
        Uuid $code,
        TransactionType $type,
        Money $amount,
        TransactionStatus $status,
        PositiveInt $origin,
        PositiveInt $destination
    ) {
        $this->code = $code;
        $this->type = $type;
        $this->amount = $amount;
        $this->status = $status;
        $this->origin = $origin;
        $this->destination = $destination;
    }

    /**
     * @return Money
     */
    public function getAmount(): Money
    {
        return $this->amount;
    }

    /**
     * @return TransactionType
     */
    public function getType(): TransactionType
    {
        return $this->type;
    }

    /**
     * @param array $data
     * @return TransactionDTO
     * @throws ReflectionException
     */
    public static function build(array $data): TransactionDTO
    {
        $code = new Uuid($data['code']);
        $type = new TransactionType($data['type']);
        $amount = Money::build([
            'amount' => $data['amount'],
            'currency' => Currency::BRL
        ]);
        $status = new TransactionStatus(TransactionStatus::PENDING);
        $origin = new PositiveInt($data['origin']);
        $destination = new PositiveInt($data['destination']);
        
        return new static($code, $type, $amount, $status, $origin, $destination);
    }

}
