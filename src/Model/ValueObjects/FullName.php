<?php

namespace App\Model\ValueObjects;

use InvalidArgumentException;

final class FullName
{
    use ValueObject;

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
