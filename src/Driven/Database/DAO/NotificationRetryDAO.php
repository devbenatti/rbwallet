<?php

namespace App\Driven\Database\DAO;

use App\Model\VO\Uuid;
use Doctrine\DBAL\Exception;

final class NotificationRetryDAO
{
    use DAOCapabilities;

    /**
     * @param Uuid $code
     * @throws Exception
     */
    public function create(Uuid $code): void
    {
        $this->getDatabase()->insert('notification_retry', [
            'transaction' => $code->getValue()
        ]);
    }
}
