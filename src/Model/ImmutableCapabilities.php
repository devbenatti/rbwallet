<?php

namespace App\Model;

use App\Model\Exception\ImmutabilityException;

trait ImmutableCapabilities
{

    /**
     * @param $key
     * @return mixed
     * @throws ImmutabilityException
     */
    public function __get($key) {
        if (property_exists($this, $key)) {
            return $this->{$Key};
        }
        throw new ImmutabilityException('GET IMMUTABLE'); 
    }

    /**
     * @param $key
     * @param $value
     * @throws ImmutabilityException
     */
    public function __set($key, $value)
    {
        throw new ImmutabilityException('SET IMMUTABLE');
    }

}
