<?php

namespace App\Driver\WebApi\Validator;

interface Validator
{
    public function validate(Validation $validation, ValidationData $data): ValidationResult;
}
