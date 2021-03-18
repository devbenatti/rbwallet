<?php

namespace App\Command\Create;

use App\Command\CommandHandler;
use App\Command\CommandHandlerCapabilities;
use App\Driven\Database\DAO\PersonDAO;
use App\Driven\Uuid\UuidGenerator;
use App\Model\User;
use App\Model\Wallet;
use App\Model\WalletRepository;
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
     * @var WalletRepository
     */
    private WalletRepository $walletRepository;
    
    /**
     * @var UuidGenerator
     */
    private UuidGenerator $uuidGenerator;

    /**
     * CreateHandler constructor.
     * @param PersonDAO $personDAO
     * @param WalletRepository $walletRepository
     * @param UuidGenerator $uuidGenerator
     */
    public function __construct(
        PersonDAO $personDAO,
        WalletRepository $walletRepository,
        UuidGenerator $uuidGenerator
    )
    {
        $this->personDAO = $personDAO;
        $this->walletRepository = $walletRepository;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @param Create $command
     * @throws ReflectionException|\Doctrine\DBAL\Exception
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
            $this->personDAO->getDatabase()->beginTransaction();

            $personId = $this->personDAO->create($user);

            $wallet = Wallet::build([
                'id' => $this->uuidGenerator->generate(),
                'balance' => 0.0,
                'ownerId' => $personId
            ]);

            $this->walletRepository->create($wallet);

            $this->personDAO->getDatabase()->commit();
        } catch (Exception $exception) {
            $this->personDAO->getDatabase()->rollBack();
            throw new exception;
        }
    }
}
