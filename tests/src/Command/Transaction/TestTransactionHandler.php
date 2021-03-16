<?php

namespace Tests\Command\Transaction;

use App\Command\Transaction\Transaction;
use App\Command\Transaction\TransactionHandler;
use App\Driven\Database\DAO\TransactionDAO;
use App\Driven\Http\Authorizer;
use App\Driven\Http\TransactionAuthorizer;
use App\Driven\Uuid\UuidAdapter;
use App\Driven\Uuid\UuidGenerator;
use App\Model\VO\DocumentType;
use App\Model\VO\FailReason;
use App\Model\VO\TransactionStatus;
use App\Model\VO\Uuid;
use App\Model\Wallet\Wallet;
use App\Model\Wallet\WalletRepository;
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
     * @covers ::handle
     * @covers ::__construct
     * @covers ::__invoke
     * @throws ReflectionException
     */
    public function testSuccess()
    {
        $walletData = $this->getWalletData();
        
        $wallet = Wallet::build($walletData);
        
        $transactionDAO = $this->createMock(TransactionDAO::class);
        
        $walletRepository = $this->createMock(WalletRepository::class);
        $walletRepository->method('findByPerson')
            ->withAnyParameters()
            ->willReturn($wallet);
        
        $authorizer = $this->createMock(TransactionAuthorizer::class);

        $code = new Uuid('6a3b23ab-2f5c-4ed0-acb0-8948d72f994a');
        
        $command = new Transaction($code,200.00,1, 2);
        
        $handler = new TransactionHandler($walletRepository, $transactionDAO, $authorizer);
        
        $handler->handle($command);
        
        static::expectNotToPerformAssertions();
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
