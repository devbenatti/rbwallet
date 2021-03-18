<?php

namespace App\Command\Create;

use App\Command\CommandHandler;
use App\Command\CommandHandlerCapabilities;
use App\Driven\Database\DAO\PersonDAO;
use App\Driven\Database\DAO\WalletDAO;
use App\Driven\Uuid\UuidGenerator;
use App\Model\User;
use App\Model\Wallet;
use Exception;
use ReflectionException;

final class CreateHandler implements CommandHandler
{
    use CommandHandlerCapabilities;

    /**
     * @var PersonDAO
     */
    private PersonDAO $personDAO;
    
    /**
     * @var WalletDAO 
     */
    private WalletDAO $walletDAO;
    
    /**
     * @var UuidGenerator
     */
    private UuidGenerator $uuidGenerator;

    /**
     * CreateHandler constructor.
     * @param PersonDAO $personDAO
     * @param WalletDAO $walletDAO
     * @param UuidGenerator $uuidGenerator
     */
    public function __construct(
        PersonDAO $personDAO,
        WalletDAO $walletDAO,
        UuidGenerator $uuidGenerator
    )
    {
        $this->personDAO = $personDAO;
        $this->walletDAO = $walletDAO;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @param Create $command
     * @throws ReflectionException
     */
    public function __invoke(Create $command)
    {
        $user = User::build([
            'name' => $command->getName(),
            'document' => $command->getDocument(),
            'email' => $command->getEmail(),
            'password' => $command->getPassword()
        ]);
        
        try {
            $this->personDAO->beginTransaction();

            $personId = $this->personDAO->create($user);

            $wallet = Wallet::build([
                'id' => $this->uuidGenerator->generate(),
                'balance' => 0.0,
                'ownerId' => $personId
            ]);

            $this->walletDAO->create($wallet);

            $this->personDAO->commit();
        } catch (Exception $exception) {
            $this->personDAO->rollBack();
            throw new exception;
        }
    }
}
