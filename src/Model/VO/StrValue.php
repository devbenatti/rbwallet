<?php

namespace App\Model\VO;

use InvalidArgumentException;

final class StrValue
{
    use VOCapabilities;

    /**
     * StrValue constructor.
     * @param $value
     */
    public function __construct($value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('Value need to be string');
        }
        
        $this->value = $value;
    }

}
