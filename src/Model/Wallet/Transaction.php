<?php

namespace App\Model\Wallet;

use App\Model\ImmutableCapabilities;
use App\Model\VO\Currency;
use App\Model\VO\DBint;
use App\Model\VO\Money;
use App\Model\VO\PositiveInt;
use App\Model\VO\TransactionStatus;
use App\Model\VO\Uuid;
use ReflectionException;

final class Transaction
{
    use ImmutableCapabilities;
    
    private Uuid $code;
    
    private Money $amount;
    
    private TransactionStatus $status;
    
    private DBint $payerId;
    
    private DBint $payeeId;
    
    public function __construct(
        Uuid $code,
        Money $amount,
        TransactionStatus $status,
        PositiveInt $payerId,
        PositiveInt $payeeId
    ) {
        $this->code = $code;
        $this->amount = $amount;
        $this->status = $status;
        $this->payerId = $payerId;
        $this->payeeId = $payeeId;
    }

    /**
     * @return Uuid
     */
    public function getCode(): Uuid
    {
        return $this->code;
    }
    
    /**
     * @return Money
     */
    public function getAmount(): Money
    {
        return $this->amount;
    }
    
    /**
     * @param array $data
     * @return Transaction
     * @throws ReflectionException
     */
    public static function build(array $data): Transaction
    {
        $code = new Uuid($data['code']);
        $amount = Money::build([
            'amount' => $data['amount'],
            'currency' => Currency::BRL
        ]);
        $status = new TransactionStatus(TransactionStatus::PROCESSING);
        $origin = new DBint($data['payerId']);
        $destination = new DBint($data['payeeId']);
        
        return new static($code, $amount, $status, $origin, $destination);
    }

}
