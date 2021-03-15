<?php

namespace Tests\Command\Transaction;

use App\Command\Transaction\Transaction;
use App\Command\Transaction\TransactionHandler;
use App\Driven\Database\DAO\TransactionDAO;
use App\Model\VO\DocumentType;
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
        $wallet = Wallet::build([
            'id' => 'b5e8469e-5048-48ce-8032-4e7cc87b4923',
            'balance' => 200.00,
            'person' => [
                'id' => 1,
                'document' => [
                    'type' => DocumentType::CPF,
                    'identifier' => '05719027540'
                ],
                'email' => 'xablau@gmail.com',
                'name' => 'Xablau testador'
            ]
        ]);
        
        $transactionDAO = $this->createMock(TransactionDAO::class);
        
        $walletRepository = $this->createMock(WalletRepository::class);
        $walletRepository->method('findByPerson')
            ->withAnyParameters()
            ->willReturn($wallet);
        
        $command = new Transaction( 200.00,1, 2);
        
        $handler = new TransactionHandler($walletRepository, $transactionDAO);
        
        $handler->handle($command);
        
        static::expectNotToPerformAssertions();
    }
}
