<?php

namespace App\Driven\Uuid;

use App\Model\VO\Uuid;

interface UuidGenerator
{
    /**
     * @return Uuid
     */
    public function generate(): Uuid;
}
