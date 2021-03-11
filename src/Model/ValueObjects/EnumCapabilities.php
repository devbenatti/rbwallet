<?php

namespace App\Model\ValueObjects;

use App\Model\Exception\InvalidEnum;
use ReflectionClass;
use ReflectionException;

trait EnumCapabilities
{
    use ValueObject;

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
        if (!$this->has($value)) {
            throw new InvalidEnum(sprintf('Invalid ENUM for value %s', $value));
        }

        $this->value = $value;
    }
    
    /**
     * @return array|mixed
     * @throws ReflectionException
     */
    private function getConstants()
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
    private function has($value)
    {
        return in_array($value, $this->getConstants(), true);
    }
}
