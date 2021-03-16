<?php


namespace App\Driven\Database\DAO;


use App\Model\Wallet\Transaction;
use App\Model\VO\Uuid;

final class TransactionDAO
{
    use DAOCapabilities;

    /**
     * @param Transaction $transaction
     */
    public function create (Transaction $transaction): void
    {
        
    }

    /**
     * @param Uuid $code
     * @param int $status
     * @param string $reason
     */
    public function updateStatus(Uuid $code, int $status, string $reason = ''): void
    {

    }
}
