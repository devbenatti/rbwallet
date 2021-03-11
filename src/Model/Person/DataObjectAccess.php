<?php

namespace App\Model\Person;

interface DataObjectAccess
{
    /**
     * @return Person
     */
    public function findById(): Person;
}
