<?php

namespace App\Driven\Database\Repository;

use App\Model\Wallet\Wallet;
use App\Model\Wallet\WalletRepository;

class MysqlWalletRepository implements WalletRepository
{
    use RepositoryCapabilities;

    public function create(): void
    {

    }

    public function findByOwner(): Wallet
    {

    }

    public function updateBalance(): void
    {
      
    }
}
