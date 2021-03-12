<?php

namespace App\Model;

use App\Model\Exception\ImmutabilityException;

interface Immutable
{
    /**
     * @param $key
     * @return mixed
     * @throws ImmutabilityException
     */
    public function __get($key);

    /**
     * @param $key
     * @param $value
     * @throws ImmutabilityException
     * @return mixed
     */
    public function __set($key, $value);
}
