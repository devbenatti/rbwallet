<?php

namespace App\Model\Wallet;

use App\Model\Exception\InsufficientFundsException;
use App\Model\ImmutableCapabilities;
use App\Model\VO\Currency;
use App\Model\VO\DBint;
use App\Model\VO\Decimal;
use App\Model\VO\Money;
use App\Model\VO\TransactionType;
use App\Model\VO\Uuid;
use ReflectionException;

final class Wallet
{
    use ImmutableCapabilities;
    
    private Uuid $id;
    
    private Money $balance;
    
    private DBint $ownerId;

    /**
     * Wallet constructor.
     * @param Uuid $id
     * @param Money $balance
     * @param DBint $ownerId
     */
    public function __construct(Uuid $id, Money $balance, DBint $ownerId)
    {
        $this->balance = $balance;
        $this->id = $id;
        $this->ownerId = $ownerId;
    }

    /**
     * @param Money $amount
     * @return bool
     */
    private function hasSufficientBalanceToTransfer(Money $amount): bool
    {
        $rest = $this->balance->sub($amount);
        
        return $rest->getAmount()->getValue() >= 0.0;
    }
    
    public function getBalance(): Decimal
    {
        return $this->balance->getAmount();
    }
    
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @param Flow $flow
     * @throws InsufficientFundsException
     */
    public function updateBalance(Flow $flow): void 
    {
        $flowType = $flow->getType();
        
        if ($flowType == Flow::OUTFLOW) {
            $this->debitAmount($flow->getValue());
        }

        if ($flowType == TransactionType::CREDIT) {
            $this->creditAmount($flow->getValue());
        }
    }

    /**
     * @param Money $amount
     * @throws InsufficientFundsException
     */
    private function debitAmount(Money $amount): void
    {
        if (!$this->hasSufficientBalanceToTransfer($amount)) {
            throw new InsufficientFundsException('insufficient funds');
        }
        
        $this->balance = $this->balance->sub($amount);
    }

    /**
     * @param Money $amount
     */
    private function creditAmount(Money $amount): void
    {
        $this->balance = $this->balance->add($amount);
    }

    /**
     * @param array $data
     * @return Wallet
     * @throws ReflectionException
     */
    public static function build(array $data): Wallet
    {
        $id = new Uuid($data['id']);
        
        $balance = Money::build([
            'amount'=> $data['balance'],
            'currency' => Currency::BRL
        ]);
        
        $ownerId = new DBint($data['ownerId']);
        
        return new static($id, $balance, $ownerId);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
          'id' => $this->id->getValue(),
          'balance' =>  $this->balance->getAmount()->getValue(),
          'ownerId' => $this->ownerId->getValue()
        ];
    }

}
