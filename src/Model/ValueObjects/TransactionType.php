<?php

namespace App\Model\ValueObjects;

final class TransactionType
{
    use EnumCapabilities;

    /**
     * @var int
     */
    const CREDIT = 1;

    /**
     * @var int
     */
    const DEBIT = 2;
}
