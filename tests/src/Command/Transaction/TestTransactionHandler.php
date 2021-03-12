<?php

namespace Tests\Command\Transaction;

use App\Command\Transaction\Transaction;
use App\Command\Transaction\TransactionHandler;
use App\Driven\Database\DAO\PersonDAO;
use App\Model\Person\Payer;
use App\Model\VO\DocumentType;
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
        $payer = Payer::build([
            'id' => '5f63d951-5439-444b-9a05-e29d80b85da5',
            'email' => 'renato.benatti@gmail.com',
            'document' => [
                'type' => DocumentType::CPF,
                'identifier' => '09749067940'
            ],
            'name' => 'Renato Benatti'
        ]);
        
        $personDAO = $this->createMock(PersonDAO::class);
        
        $personDAO->method('getById')
            ->withAnyParameters()
            ->willReturn($payer);

        $command = new Transaction( 200.00,1, 2);
        
        $handler = new TransactionHandler($personDAO);
        
        $handler->handle($command);
        
        static::expectNotToPerformAssertions();
    }
}
