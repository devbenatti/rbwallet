<?php

namespace App\Model\VO;

use InvalidArgumentException;

class PositiveInt
{
    use VOCapabilities;
    
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
