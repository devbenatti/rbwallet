<?php

namespace App\Driven\Database\Repository;

use App\Model\Wallet\Wallet;
use App\Model\Wallet\WalletRepository;
use Doctrine\DBAL\Exception;
use ReflectionException;

class MysqlWalletRepository implements WalletRepository
{
    use RepositoryCapabilities;

    public function create(): void
    {

    }

    /**
     * @param Wallet $wallet
     * @throws Exception
     */
    public function updateBalance(Wallet $wallet): void
    {
        $this->database->update('wallet', [
            'balance' => $wallet->getBalance()->getValue()
        ], [
            'id' => $wallet->getId()->getValue()
        ]);
    }

    /**
     * @param int $personId
     * @return Wallet|null
     * @throws ReflectionException|Exception
     */
    public function findByPerson(int $personId): ?Wallet
    {

        $data = $this->database->createQueryBuilder()
            ->select([
                'w.id',
                'w.balance',
                'w.user_id'
            ])
            ->from('wallet', 'w')
            ->where('w.user_id = :id')
            ->setParameter('id', $personId)
            ->execute()
            ->fetchAssociative();
        
        if (!$data) {
            throw new Exception();
        }
        
        return Wallet::build([
            'id' => $data['id'],
            'balance' => (float)$data['balance'],
            'ownerId' => (int)$data['user_id']
        ]);
    }
}
