<?php

namespace App\Driver\WebApi\Middleware;

use Exception;

final class BadRequestException extends Exception
{
    private array $errors;

    /**
     * BadRequestException constructor.
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct('', 400);
        
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->errors;
    }
}
