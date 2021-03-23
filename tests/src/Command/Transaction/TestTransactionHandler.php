<?php

namespace Tests\Command\Transaction;

use App\Command\Transaction\Transaction;
use App\Command\Transaction\TransactionHandler;
use App\Driven\Database\DAO\NotificationRetryDAO;
use App\Driven\Database\DAO\TransactionDAO;
use App\Driven\Database\DAO\WalletDAO;
use App\Driven\Http\NotifierUnavailableException;
use App\Driven\Http\TransactionAuthorizer;
use App\Driven\Http\TransactionNotifier;
use App\Driven\Http\TransactionUnauthorizedException;
use App\Model\VO\FailReason;
use App\Model\VO\TransactionStatus;
use App\Model\VO\Uuid;
use App\Model\Wallet;
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
        
        $walletDAO = $this->createMock(WalletDAO::class);
        $walletDAO->method('findByPerson')
            ->withConsecutive([1], [2])
            ->willReturnOnConsecutiveCalls($payerWallet, $payeeWallet);
        
        $walletDAO->expects(static::exactly(2))
            ->method('updateBalance')
            ->withConsecutive([$payerUpdatedWallet], [$payeeUpdatedWallet]);
        
        $notificationDAO = $this->createMock(NotificationRetryDAO::class);
        
        $authorizer = $this->createMock(TransactionAuthorizer::class);
        $notifier = $this->createMock(TransactionNotifier::class);
        
        $command = new Transaction($code,199.99,1, 2);
        
        $handler = new TransactionHandler(
            $walletDAO,
            $transactionDAO,
            $authorizer,
            $notifier,
            $notificationDAO
        );
        
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

        $walletDAO = $this->createMock(WalletDAO::class);
        $walletDAO->method('findByPerson')
            ->withAnyParameters()
            ->willReturn($wallet);

        $notificationDAO = $this->createMock(NotificationRetryDAO::class);

        $authorizer = $this->createMock(TransactionAuthorizer::class);
        $notifier = $this->createMock(TransactionNotifier::class);
        
        $command = new Transaction($code,500.00,1, 2);

        $handler = new TransactionHandler(
            $walletDAO,
            $transactionDAO,
            $authorizer,
            $notifier,
            $notificationDAO
        );
        
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

        $walletDAO = $this->createMock(WalletDAO::class);
        $walletDAO->method('findByPerson')
            ->withAnyParameters()
            ->willReturn($wallet);

        $notificationDAO = $this->createMock(NotificationRetryDAO::class);

        $notifier = $this->createMock(TransactionNotifier::class);
        
        $authorizer = $this->createMock(TransactionAuthorizer::class);
        $authorizer->method('authorize')
            ->withAnyParameters()
            ->willThrowException(new TransactionUnauthorizedException());

        $command = new Transaction($code,100.00,1, 2);

        $handler = new TransactionHandler(
            $walletDAO,
            $transactionDAO,
            $authorizer,
            $notifier,
            $notificationDAO
        );

        $handler->handle($command);
    }

    public function testFailNotificationShouldCreateNotificationRetry()
    {
        $walletData = $this->getWalletData();

        $wallet = Wallet::build($walletData);

        $code = new Uuid('6a3b23ab-2f5c-4ed0-acb0-8948d72f994a');

        $transactionDAO = $this->createMock(TransactionDAO::class);
        $transactionDAO->expects(static::once())
            ->method('updateStatus')
            ->withConsecutive([$code, TransactionStatus::SUCCESS]);
        
        $walletDAO = $this->createMock(WalletDAO::class);
        $walletDAO->method('findByPerson')
            ->withAnyParameters()
            ->willReturn($wallet);

        $notificationDAO = $this->createMock(NotificationRetryDAO::class);
        $notificationDAO->expects(static::once())
            ->method('create')
            ->with($code);

        $notifier = $this->createMock(TransactionNotifier::class);
        $notifier->method('notify')
            ->withAnyParameters()
            ->willThrowException(new NotifierUnavailableException());
        
        $authorizer = $this->createMock(TransactionAuthorizer::class);
        
        $command = new Transaction($code,100.00,1, 2);

        $handler = new TransactionHandler(
            $walletDAO,
            $transactionDAO,
            $authorizer,
            $notifier,
            $notificationDAO
        );

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
