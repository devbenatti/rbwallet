<?php

namespace App\Model\Wallet;

use App\Model\Exception\InsufficientFundsException;
use App\Model\ImmutableCapabilities;
use App\Model\VO\Currency;
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
    
    private Person $person;

    /**
     * Wallet constructor.
     * @param Uuid $id
     * @param Money $balance
     * @param Person $person
     */
    public function __construct(Uuid $id, Money $balance, Person $person)
    {
        $this->balance = $balance;
        $this->id = $id;
        $this->person = $person;
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
        
        $owner = Person::build([
            'id' => $data['person']['id'],
            'document' => [
                'type' => $data['person']['document']['type'],
                'identifier' => $data['person']['document']['identifier']
            ],
            'email' => $data['person']['email'],
            'name' => $data['person']['name']
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
          'person' => $this->person->toArray()
        ];
    }

}
