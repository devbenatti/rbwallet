<?php

namespace App\Model;

interface WalletRepository
{
    public function create(Wallet $wallet): void;
    
    public function findByPerson(int $personId): ?Wallet;
    
    public function updateBalance(Wallet $wallet): void;
}
