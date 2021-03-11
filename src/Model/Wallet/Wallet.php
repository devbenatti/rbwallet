<?php

namespace App\Model\Wallet;

use App\Model\Exception\InsufficientFundsException;
use App\Model\Immutability;
use App\Model\Person\Owner;
use App\Model\Person\Person;
use App\Model\Transaction\Transaction;
use App\Model\ValueObjects\Currency;
use App\Model\ValueObjects\Decimal;
use App\Model\ValueObjects\Money;
use App\Model\ValueObjects\PositiveInt;
use App\Model\ValueObjects\TransactionType;
use ReflectionException;

final class Wallet
{
    use Immutability;
    
    private PositiveInt $id;
    
    private Money $balance;
    
    private Person $owner;

    /**
     * Wallet constructor.
     * @param PositiveInt $id
     * @param Money $balance
     * @param Person $owner
     */
    public function __construct(PositiveInt $id, Money $balance, Person $owner)
    {
        $this->balance = $balance;
        $this->id = $id;
        $this->owner = $owner;
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

    /**
     * @param Transaction $transaction
     * @throws InsufficientFundsException
     */
    public function updateBalance(Transaction $transaction): void 
    {
        $transactionType = $transaction->getType()->getValue();
        
        if ($transactionType == TransactionType::DEBIT) {
            $this->debitAmount($transaction->getAmount());
        }

        if ($transactionType == TransactionType::CREDIT) {
            $this->creditAmount($transaction->getAmount());
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
        $id = new PositiveInt($data['id']);
        
        $balance = Money::build([
            'amount'=> $data['balance'],
            'currency' => Currency::BRL
        ]);
        
        $owner = Owner::build([
            'id' => $data['owner']['id'],
            'document' => [
                'type' => $data['owner']['document']['type'],
                'identifier' => $data['owner']['document']['identifier']
            ],
            'email' => $data['owner']['email'],
            'name' => $data['owner']['name']
        ]);
        
        return new static($id, $balance, $owner);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
          'id' => $this->id->getValue(),
          'balance' =>  $this->balance->getAmount()->getValue(),
          'owner' => $this->owner->toArray()
        ];
    }

}
