<?php

namespace App\Command\Create;

use App\Command\Command;
use App\Model\ImmutableCapabilities;

final class Create implements Command
{
    use ImmutableCapabilities;
    
    private string $name;
    
    private string $document;
    
    private string $password;
    
    private string $email;

    public function __construct(string $name, string $document, string $password, string $email)
    {
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->document = $document;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDocument(): string
    {
        return $this->document;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    
}
