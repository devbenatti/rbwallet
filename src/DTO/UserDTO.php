<?php

namespace App\DTO;

use App\Model\ImmutableCapabilities;
use App\Model\VO\Document;
use App\Model\VO\Email;
use App\Model\VO\FullName;
use App\Model\VO\StrValue;
use ReflectionException;

final class UserDTO
{
    use ImmutableCapabilities;
    
    private Email $email;
    
    private Document $document;
    
    private FullName $name;
   
    private StrValue $password;

    /**
     * UserDTO constructor.
     * @param FullName $name
     * @param Document $document
     * @param Email $email
     * @param StrValue $password
     */
    public function __construct(
        FullName $name,
        Document $document,
        Email $email,
        StrValue $password
    )
    {
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->document = $document;
    }
    
    /**
     * @param array $data
     * @return UserDTO
     * @throws ReflectionException
     */
    public static function build(array $data): UserDTO
    {
        $name = new FullName($data['name']);
        $document = Document::build($data['document']['type'], $data['document']['identifier']);
        $email = new Email($data['email']);
        $password = new StrValue($data['password']);
        
        return new static($name, $document, $email, $password);
    }
    
    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name->getValue(),
            'document' => $this->document->toArray(),
            'email' =>  $this->email->getValue(),
            'password' => $this->password->getValue()
        ];
    }
    
    
}
