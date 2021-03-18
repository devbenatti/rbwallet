<?php

namespace App\Driven\Database\DAO;

use App\Model\Wallet;

use Doctrine\DBAL\Exception;
use ReflectionException;

final class WalletDAO
{
    use DAOCapabilities;

    public function create(Wallet $wallet): void
    {
        $this->database->insert('wallet', [
            'id' => $wallet->getId()->getValue(),
            'balance' => $wallet->getBalance()->getValue(),
            'user' => $wallet->getOwnerId()->getValue(),
        ]);
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
                'w.user'
            ])
            ->from('wallet', 'w')
            ->where('w.user = :id')
            ->setParameter('id', $personId)
            ->execute()
            ->fetchAssociative();
        
        if (!$data) {
            throw new Exception();
        }
        
        return Wallet::build([
            'id' => $data['id'],
            'balance' => (float)$data['balance'],
            'ownerId' => (int)$data['user']
        ]);
    }
}
