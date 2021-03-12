<?php

namespace App\Model\VO;

use InvalidArgumentException;

final class FullName
{
    use VOCapabilities;

    /**
     * FullName constructor.
     * @param $value
     */
    public function __construct($value)
    {
        if (!preg_match('/^[a-zA-Z]+(?:\s[a-zA-Z]+)+$/', $value)) {
            throw new InvalidArgumentException('Invalid full name');
        }
        
        $this->value = $value;
    }
}
