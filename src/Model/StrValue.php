<?php


namespace App\Model;


use InvalidArgumentException;

final class StrValue
{
    use ValueObject;

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
