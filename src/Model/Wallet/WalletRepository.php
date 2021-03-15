<?php

namespace App\Model\Wallet;

interface WalletRepository
{
    public function create(): void;

    /**
     * @param int $personId
     * @return Wallet|null
     */
    public function findByPerson(int $personId): ?Wallet;
    
    public function updateBalance(Wallet $wallet): void;
}
