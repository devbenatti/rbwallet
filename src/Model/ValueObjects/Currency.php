<?php

namespace App\Model\ValueObjects;

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
