<?php

namespace App\Model\ValueObjects;

final class TransactionStatus
{
    use EnumCapabilities;

    /**
     * @var int
     */
    const PENDING = 1;

    /**
     * @var int
     */
    const COMPLETED = 2;

    /**
     * @var int
     */
    const NOT_COMPLETED = 3;
    
}
