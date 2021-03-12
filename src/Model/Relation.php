<?php

namespace App\Model;

interface Relation extends Immutable
{
    public function toArray(): array;
}
