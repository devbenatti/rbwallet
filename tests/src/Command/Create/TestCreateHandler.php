<?php

namespace Tests\Command\Create;

use App\Command\Create\Create;
use App\Command\Create\CreateHandler;
use App\Driven\Database\DAO\PersonDAO;
use App\Driven\Uuid\UuidGenerator;
use App\Model\VO\Uuid;
use App\Model\Wallet;
use App\Model\WalletRepository;
use PHPUnit\Framework\TestCase;

class TestCreateHandler extends TestCase
{
    public function testSuccess()
    {
        $command = new Create(
            "Xablau Testador",
            '90884710025',
            'xablau',
            'xablau@gmail.com'
        );
        
        $personDAO = $this->createMock(PersonDAO::class);
        $personDAO->method('create')
            ->withAnyParameters()
            ->willReturn(1);
        
        $walletId = new Uuid('761be75f-0489-4e4a-aafb-06f2c253d263');
        
        $uuidGenerator = $this->createMock(UuidGenerator::class);
        $uuidGenerator->method('generate')
            ->withAnyParameters()
            ->willReturn($walletId);
        
        $wallet = Wallet::build([
            'id' => $walletId->getValue(),
            'balance' => 0.0,
            'ownerId' => 1
        ]);
        
        $walletRepository = $this->createMock(WalletRepository::class);
        $walletRepository->expects(static::once())
            ->method('create')
            ->with($wallet);
        
        $handler = new CreateHandler($personDAO, $walletRepository, $uuidGenerator);
        $handler->handle($command);
    }
}
