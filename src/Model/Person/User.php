<?php

namespace App\Model\Person;

use App\Model\Immutability;
use App\Model\ValueObjects\Document;
use App\Model\ValueObjects\Email;
use App\Model\ValueObjects\FullName;
use App\Model\ValueObjects\StrValue;
use ReflectionException;

final class User
{
    use Immutability;
    
    private Email $email;
    
    private Document $document;
    
    private FullName $name;
   
    private StrValue $password;

    /**
     * User constructor.
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
     * @return User
     * @throws ReflectionException
     */
    public static function build(array $data): User
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
