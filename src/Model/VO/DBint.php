<?php

namespace App\Model\VO;

use InvalidArgumentException;

final class DBint extends PositiveInt
{

    /**
     * @var int
     */
    private const MIN_DB_INT = 1;

    /**
     * @var int
     */
    private const MAX_DB_INT = 2147483647;
    
    /**
     * PositiveInt constructor.
     * @param $value
     */
    public function __construct($value)
    {
        if ($value < static::MIN_DB_INT || $value > static::MAX_DB_INT) {
            throw new InvalidArgumentException(sprintf('Invalid value for %s', $value));
        }
        parent::__construct($value);
    }
}
