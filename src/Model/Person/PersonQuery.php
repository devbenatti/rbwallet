<?php

namespace App\Model\Person;

interface PersonQuery
{
    /**
     * @return Person
     */
    public function findById(): Person;
}
