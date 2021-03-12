<?php

namespace App\Command\Transaction;

use App\Command\CommandHandler;
use App\Command\CommandHandlerCapabilities;
use App\Driven\Database\DAO\PersonDAO;
use ReflectionException;

final class TransactionHandler implements CommandHandler
{

    use CommandHandlerCapabilities;
    
    private PersonDAO $personDAO;
    
    /**
     * TransactionHandler constructor.
     * @param PersonDAO $personDAO
     */
    public function __construct(PersonDAO $personDAO)
    {
        $this->personDAO = $personDAO;
    }

    /**
     * @param Transaction $transaction
     * @return void
     * @throws ReflectionException
     */
    public function __invoke(Transaction $transaction): void
    {
        $person = $this->personDAO->getById($transaction->getPayer());
    }
}
