<?php

namespace App\Driver\WebApi\Validator;

use App\Model\ImmutableCapabilities;

final class ValidationData
{
    use ImmutableCapabilities;
    
    private object $data;
    
    public function __construct(array $data)
    {
        $this->data = (object) $data;
    }
    
    public function getData(): object
    {
        return $this->data;
    }
}
