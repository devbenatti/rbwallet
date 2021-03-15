<?php

namespace App\Driven\Database\Repository;

use App\Model\VO\DocumentType;
use App\Model\Wallet\Wallet;
use App\Model\Wallet\WalletRepository;
use ReflectionException;

class MysqlWalletRepository implements WalletRepository
{
    use RepositoryCapabilities;

    public function create(): void
    {

    }

    public function updateBalance(Wallet $wallet): void
    {
      
    }

    /**
     * @param int $personId
     * @return Wallet|null
     * @throws ReflectionException
     */
    public function findByPerson(int $personId): ?Wallet
    {
        return Wallet::build([
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
    }
}
