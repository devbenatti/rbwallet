<?php

namespace Tests\Model\Wallet;

use App\Model\Exception\InsufficientFundsException;
use App\Model\Wallet\Flow;
use App\Model\Wallet\Transaction;
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
        
        $inFlow = Flow::buildCashInflow($transaction);
        
        $wallet->updateBalance($inFlow);
        
        $balance = $walletData['balance'] + $transactionData['amount'];
        
        static::assertEquals($balance, $wallet->getBalance()->getValue());
    }
    
    public function testUpdateBalanceShouldReturnInsufficientFundsException()
    {
        $walletData = $this->getWalletData([
            'balance' => 10.0
        ]);

        $wallet = Wallet::build($walletData);

        $transactionData = $this->getTransactionData();

        $transaction = Transaction::build($transactionData);
        $outFlow = Flow::buildCashOutflow($transaction);

        $this->expectException(InsufficientFundsException::class);
        
        $wallet->updateBalance($outFlow);
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
                'id' => '6a3b23ab-2f5c-4ed0-acb0-8948d72f994a',
                'balance' => 200.00,
                'ownerId' => 1
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
                'amount' => 100.00,
                'payerId' => 1,
                'payeeId' => 2
            ], $data)
        );
    }
}
