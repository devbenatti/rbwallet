<?php

namespace App\Model;

use App\Model\VO\Document;
use App\Model\VO\Email;
use App\Model\VO\FullName;
use App\Model\VO\StrValue;
use ReflectionException;

final class User
{
    /**
     * @var StrValue
     */
    private StrValue $password;
    
    /**
     * @var FullName
     */
    private FullName $name;
    
    /**
     * @var Email
     */
    private Email $email;
    
    /**
     * @var Document
     */
    private Document $document;

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
     * @return StrValue
     */
    public function getPassword(): StrValue
    {
        return $this->password;
    }

    /**
     * @return FullName
     */
    public function getName(): FullName
    {
        return $this->name;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->document;
    }
    
    /**
     * @param array $data
     * @return User
     * @throws ReflectionException
     */
    public static function build(array $data): User
    {
        $name = new FullName($data['name']);
        $document = Document::build($data['document']);
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
