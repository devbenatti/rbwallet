<?php

namespace App\Model\VO;

use App\Model\Exception\InvalidEnum;
use ReflectionException;

final class Currency
{
    use EnumCapabilities;
    
    /**
     * @var string
     */
    const BRL = 'BRL';
}
