<?php

namespace App\Model\VO;

use App\Model\Exception\InvalidEnum;
use ReflectionClass;
use ReflectionException;

trait EnumCapabilities
{
    use VOCapabilities;

    /**
     * @var mixed
     */
    private static $constCache = NULL;

    /**
     * Currency constructor.
     * @param $value
     * @throws ReflectionException
     */
    public function __construct($value)
    {
        if (!static::has($value)) {
            throw new InvalidEnum(sprintf('Invalid ENUM for value %s', $value));
        }

        $this->value = $value;
    }
    
    /**
     * @return array|mixed
     * @throws ReflectionException
     */
    public static function getConstants()
    {
        if (self::$constCache == NULL) {
            self::$constCache = [];
        }

        $calledClass = get_called_class();

        if (!array_key_exists($calledClass, self::$constCache)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCache[$calledClass] = $reflect->getConstants();
        }

        return self::$constCache[$calledClass];
    }

    /**
     * @param $value
     * @return bool
     * @throws ReflectionException
     */
    public static function has($value)
    {
        return in_array($value, static::getConstants(), true);
    }
}
