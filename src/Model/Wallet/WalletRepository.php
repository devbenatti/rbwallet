<?php

namespace App\Model\Wallet;

interface WalletRepository
{
    public function create(): void;
    
    public function findByOwner(): Wallet;
    
    public function updateBalance(): void;
}
