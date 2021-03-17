<?php

namespace App\Driver\WebApi\Validator;

use JsonSchema\Validator as JsonValidator;

final class JsonSchemaValidator implements Validator
{
    public function validate(Validation $validation, ValidationData $validationData): ValidationResult
    {
        $errors = [];
        
        $validator = new JsonValidator();
        
        $data = $validationData->getData();
        
        $validator->validate($data, $validation->getSpecification());
        
        if (!$validator->isValid()) {
            foreach ($validator->getErrors() as $error) {
                array_push($errors, ['message' => $error['message'], 'location' => $error['property']]);
            }
        }
        
        return new ValidationResult($errors);
    }
}
