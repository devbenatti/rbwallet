<?php

namespace App\Driver\WebApi\Validator;

use App\Model\ImmutableCapabilities;

final class ValidationResult
{
    use ImmutableCapabilities;
    
    private array $errors;
    
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }
    
    public function isValid(): bool
    {
        return empty($this->errors);
    }
    
    public function getErrors(): array
    {
        return $this->errors;
    }
}
