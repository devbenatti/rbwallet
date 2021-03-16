<?php

namespace App\Model\VO;

final class TransactionStatus
{
    use EnumCapabilities;

    /**
     * @var int
     */
    const PROCESSING = 1;

    /**
     * @var int
     */
    const SUCCESS = 2;

    /**
     * @var int
     */
    const FAILED = 3;
}
