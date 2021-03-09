<?php

namespace App\Model;

use ReflectionException;

final class User
{
    use Immutability;
    
    private StrValue $password;
    
    private Email $email;
    
    private Document $document;
    
    private FullName $name;

    /**
     * User constructor.
     * @param FullName $name
     * @param Document $document
     * @param Email $email
     * @param StrValue $password
     */
    public function __construct(FullName $name, Document $document, Email $email, StrValue $password)
    {
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
        $this->document = $document;
    }


    /**
     * @return bool
     */
    public function isMerchant(): bool
    {
        return !$this->document->isCPF();
    }

    /**
     * @param array $data
     * @return User
     * @throws ReflectionException
     */
    public static function build(array $data): User
    {
        $name = new FullName($data['name']);
        $document = Document::build($data['document']['type'], $data['document']['identification']);
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
