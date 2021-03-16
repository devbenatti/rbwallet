<?php

namespace App\Model\VO;

final class FailReason
{
    use EnumCapabilities;

    /**
     * @var string
     */
    const INSUFFICIENT_FUNDS = 'insufficient_funds';

    /**
     * @var string
     */
    const UNAUTHORIZED = 'unauthorized';

    /**
     * @var string
     */
    const UNKNOWN = 'unknown';
    
}
