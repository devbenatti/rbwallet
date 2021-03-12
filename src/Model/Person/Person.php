<?php

namespace App\Model\Person;

use App\Model\VO\Document;
use App\Model\VO\Email;
use App\Model\VO\FullName;
use App\Model\VO\Uuid;
use App\Model\Relation;

interface Person extends Relation
{
    public function getDocument(): Document;
    
    public function getId(): Uuid;
    
    public function getEmail(): Email;
    
    public function getName(): FullName;
}
