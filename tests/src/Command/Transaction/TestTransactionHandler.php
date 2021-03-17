<?php

namespace Tests\Command\Transaction;

use App\Command\Transaction\Transaction;
use App\Command\Transaction\TransactionHandler;
use App\Driven\Database\DAO\TransactionDAO;
use App\Driven\Http\Authorizer;
use App\Driven\Http\TransactionAuthorizer;
use App\Driven\Http\TransactionUnauthorizedException;
use App\Model\VO\FailReason;
use App\Model\VO\TransactionStatus;
use App\Model\VO\Uuid;
use App\Model\Wallet;
use App\Model\WalletRepository;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * Class TestTransactionHandler
 * @package Tests\Command\Transaction
 * @coversDefaultClass TransactionHandler
 */
class TestTransactionHandler extends TestCase
{

    /**
     * @throws ReflectionException
     */
    public function testSuccess()
    {
        $code = new Uuid('6a3b23ab-2f5c-4ed0-acb0-8948d72f994a');
        
        $payerWalletData = $this->getWalletData();
        $payerUpdatedWalletData = $this->getWalletData([
            'balance' => 0.01
        ]);
        
        $payeeWalletData = $this->getWalletData([
            'ownerId' => 2
        ]);
        $payeeUpdatedWalletData = $this->getWalletData([
            'ownerId' => 2,
            'balance' => 399.99
        ]);
        
        $payerWallet = Wallet::build($payerWalletData);
        $payerUpdatedWallet = Wallet::build($payerUpdatedWalletData);
        
        $payeeWallet = Wallet::build($payeeWalletData);
        $payeeUpdatedWallet = Wallet::build($payeeUpdatedWalletData);
        
        $transactionDAO = $this->createMock(TransactionDAO::class);
        $transactionDAO->expects(static::once())
            ->method('updateStatus')
            ->withConsecutive([$code, TransactionStatus::SUCCESS]);
        
        $walletRepository = $this->createMock(WalletRepository::class);
        $walletRepository->method('findByPerson')
            ->withConsecutive([1], [2])
            ->willReturnOnConsecutiveCalls($payerWallet, $payeeWallet);
        
        $walletRepository->expects(static::exactly(2))
            ->method('updateBalance')
            ->withConsecutive([$payerUpdatedWallet], [$payeeUpdatedWallet]);
        
        $authorizer = $this->createMock(TransactionAuthorizer::class);
        
        $command = new Transaction($code,199.99,1, 2);
        
        $handler = new TransactionHandler($walletRepository, $transactionDAO, $authorizer);
        
        $handler->handle($command);
    }
    
    public function testWalletWithoutBalanceShouldUpdateTransactionToNotCompleted()
    {
        $walletData = $this->getWalletData();

        $wallet = Wallet::build($walletData);

        $code = new Uuid('6a3b23ab-2f5c-4ed0-acb0-8948d72f994a');
        
        $transactionDAO = $this->createMock(TransactionDAO::class);
        $transactionDAO->expects(static::once())
            ->method('updateStatus')
            ->withConsecutive([$code, TransactionStatus::FAILED, FailReason::INSUFFICIENT_FUNDS]);

        $walletRepository = $this->createMock(WalletRepository::class);
        $walletRepository->method('findByPerson')
            ->withAnyParameters()
            ->willReturn($wallet);
        
        $authorizer = $this->createMock(Authorizer::class);
        
        $command = new Transaction($code,500.00,1, 2);

        $handler = new TransactionHandler($walletRepository, $transactionDAO, $authorizer);
        
        $handler->handle($command);
    }

    public function testTransactionUnauthorizedShouldUpdateTransactionToUnauthorized()
    {
        $walletData = $this->getWalletData();

        $wallet = Wallet::build($walletData);

        $code = new Uuid('6a3b23ab-2f5c-4ed0-acb0-8948d72f994a');

        $transactionDAO = $this->createMock(TransactionDAO::class);
        $transactionDAO->expects(static::once())
            ->method('updateStatus')
            ->withConsecutive([$code, TransactionStatus::FAILED, FailReason::UNAUTHORIZED]);

        $walletRepository = $this->createMock(WalletRepository::class);
        $walletRepository->method('findByPerson')
            ->withAnyParameters()
            ->willReturn($wallet);

        $authorizer = $this->createMock(Authorizer::class);
        $authorizer->method('authorize')
            ->withAnyParameters()
            ->willThrowException(new TransactionUnauthorizedException());

        $command = new Transaction($code,100.00,1, 2);

        $handler = new TransactionHandler($walletRepository, $transactionDAO, $authorizer);

        $handler->handle($command);
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
}
