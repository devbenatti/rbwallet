<?php

namespace App\Model;

use InvalidArgumentException;

final class Email
{
    use ValueObject;

    /**
     * Email constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!is_string($value) || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid value for Email');
        }
        
        $this->value = $value;
    }

}
