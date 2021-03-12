<?php

namespace App\Model\VO;

use InvalidArgumentException;

final class Decimal
{
    use VOCapabilities;
    
    /**
     * Decimal constructor.
     * @param $value
     */
    public function __construct($value) {
        if (!is_float($value)) {
            throw new InvalidArgumentException('Value need to be float');
        }
        
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function format()
    {
        return number_format($this->getValue(), 2, '.', '');
    }

    /**
     * @param Decimal $value
     * @return $this
     */
    public function add(Decimal $value)
    {
        return new static(
            floatval(
                bcadd(
                    $this->format(),
                    $value->format(),
                    2
                )
            )
        );
    }

    /**
     * @param Decimal $value
     * @return $this
     */
    public function sub(Decimal $value)
    {
        return new static(
            floatval(
                bcsub(
                    $this->format(),
                    $value->format(),
                    2
                )
            )
        );
    }
}
