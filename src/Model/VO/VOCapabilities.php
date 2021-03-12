<?php

namespace App\Model\VO;

use App\Model\ImmutableCapabilities;

trait VOCapabilities
{
    use ImmutableCapabilities;
    
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
