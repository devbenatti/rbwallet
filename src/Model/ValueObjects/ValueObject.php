<?php

namespace App\Model\ValueObjects;

use App\Model\Immutability;

trait ValueObject
{
    use Immutability;
    
    /**
     * @var mixed type
     */
    private $value;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @return bool
     */
    public function valueEquals($value)
    {
        return $this->value === $value;
    }
        

}
