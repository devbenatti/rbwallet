<?php

namespace App\Driven\Uuid;

use App\Model\VO\Uuid;

final class UuidAdapter implements UuidGenerator
{
    /**
     * @return Uuid
     */
    public function generate(): Uuid
    {
        return new Uuid(\Ramsey\Uuid\Uuid::uuid4());
    }
}
