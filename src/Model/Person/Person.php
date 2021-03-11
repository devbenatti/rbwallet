<?php

namespace App\Model\Person;

use App\Model\ValueObjects\Document;
use App\Model\ValueObjects\Email;
use App\Model\ValueObjects\FullName;
use App\Model\ValueObjects\Uuid;
use App\Model\Relation;

interface Person extends Relation
{
    public function getDocument(): Document;
    
    public function getId(): Uuid;
    
    public function getEmail(): Email;
    
    public function getName(): FullName;
}
