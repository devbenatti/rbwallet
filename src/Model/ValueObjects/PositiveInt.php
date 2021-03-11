<?php

namespace App\Model\ValueObjects;

use InvalidArgumentException;

final class PositiveInt
{
    use ValueObject;
    
    /**
     * PositiveInt constructor.
     * @param $value
     */
    public function __construct($value) {
        if (!is_int($value) || $value <= 0 ) {
            throw new InvalidArgumentException(sprintf('Invalid value for %s', $value));
        }
        
        $this->value = $value;
    }
}
