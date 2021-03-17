<?php


namespace App\Driven\Database\DAO;


use App\Model\Transaction;
use App\Model\VO\Uuid;
use Doctrine\DBAL\Exception;

final class TransactionDAO
{
    use DAOCapabilities;

    /**
     * @param Transaction $transaction
     * @throws Exception
     */
    public function create (Transaction $transaction): void
    {
        $this->getDatabase()->insert('transaction', [
            'code' => $transaction->getCode()->getValue(),
            'amount' => $transaction->getAmount()->getValue(),
            'status' => $transaction->getStatus()->getValue(),
            'payer' => $transaction->getPayerId()->getValue(),
            'payee' => $transaction->getPayeeId()->getValue(),
        ]);
    }

    /**
     * @param Uuid $code
     * @param int $status
     * @param string $reason
     * @throws Exception
     */
    public function updateStatus(Uuid $code, int $status, string $reason = ''): void
    {
        $this->database->update('transaction', [
            'status' => $status,
            'failed_reason' => $reason
        ], [
            'code' => $code->getValue()
        ]);
    }
}
