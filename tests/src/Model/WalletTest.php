<?php

namespace Tests\Model;

use App\Model\Exception\InsufficientFundsException;
use App\Model\Transaction\Transaction;
use App\Model\ValueObjects\DocumentType;
use App\Model\ValueObjects\TransactionType;
use App\Model\Wallet\Wallet;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class WalletTest extends TestCase
{
    
    public function testSuccess()
    {
        $data = $this->getWalletData();

        $wallet = Wallet::build($data);
        
        static::assertEquals($data, $wallet->toArray());
    }
    
    public function testUpdateBalanceSuccess()
    {
        $walletData = $this->getWalletData();

        $wallet = Wallet::build($walletData);
        
        $transactionData = $this->getTransactionData();
        
        $transaction = Transaction::build($transactionData);
        
        $wallet->updateBalance($transaction);
        
        $balance = $walletData['balance'] + $transactionData['amount'];
        
        static::assertEquals($balance, $wallet->getBalance()->getValue());
    }
    
    public function testUpdateBalanceShouldReturnException()
    {
        $walletData = $this->getWalletData([
            'balance' => 10.0
        ]);

        $wallet = Wallet::build($walletData);

        $transactionData = $this->getTransactionData([
            'type' => TransactionType::DEBIT
        ]);

        $transaction = Transaction::build($transactionData);

        $this->expectException(InsufficientFundsException::class);
        
        $wallet->updateBalance($transaction);
    }
    
    /**
     * @throws ReflectionException
     */
    public function testGetBalance()
    {
        $data = $this->getWalletData();
        
        $wallet = Wallet::build($data);
        
        static::assertEquals($data['balance'], $wallet->getBalance()->getValue());
    }
    
    
    /**
     * @param array $data
     * @return array
     */
    private function getWalletData(array $data = [])
    {
        return array_filter(
            array_merge([
                'id' => 1,
                'balance' => 200.00,
                'owner' => [
                    'id' => '5f63d951-5439-444b-9a05-e29d80b85da5',
                    'document' => [
                        'type' => DocumentType::CPF,
                        'identifier' => '05719027540'
                    ],
                    'email' => 'xablau@gmail.com',
                    'name' => 'Xablau testador'
                ]
            ], $data)
        );
    }

    /**
     * @param array $data
     * @return array
     */
    private function getTransactionData(array $data = [])
    {
        return array_filter(
            array_merge([
                'code' => '1a883e5d-1552-4af9-a214-2825d7e1e2b8',
                'type' => TransactionType::CREDIT,
                'amount' => 100.00,
                'origin' => 1,
                'destination' => 2
            ], $data)
        );
    }
}
