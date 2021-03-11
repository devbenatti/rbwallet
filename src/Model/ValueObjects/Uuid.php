<?php

namespace App\Model\ValueObjects;

use InvalidArgumentException;

final class Uuid
{
    use ValueObject;
    
    public function __construct(string $value)
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException(sprintf('Invalid value for %s', $value));
        }
        
        $this->value = $value;
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isValid(string $value): bool
    {
        $pattern = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        
        return (bool)preg_match($pattern, $value);
    }
}
