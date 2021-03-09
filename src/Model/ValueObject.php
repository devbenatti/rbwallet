<?php

namespace App\Model;

trait ValueObject
{
    use Immutability;
    
    /**
     * @var mixed type
     */
    protected $value;

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
