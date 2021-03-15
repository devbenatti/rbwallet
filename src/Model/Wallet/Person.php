<?php

namespace App\Model\Wallet;

use App\Model\ImmutableCapabilities;
use App\Model\VO\DBint;
use App\Model\VO\Document;
use App\Model\VO\DocumentType;
use App\Model\VO\Email;
use App\Model\VO\FullName;
use ReflectionException;

final class Person
{
    use ImmutableCapabilities;

    private DBint $id;

    private Document $document;
    
    private Email $email;
    
    private FullName $name;


    /**
     * Payer constructor.
     * @param DBint $id
     * @param Document $document
     * @param Email $email
     * @param FullName $name
     */
    public function __construct(DBint $id, Document $document, Email $email, FullName $name)
    {
        $this->id = $id;
        $this->document = $document;
        $this->email = $email;
        $this->name = $name;
    }
    
    /**
     * @return bool
     */
    public function isMerchant(): bool
    {
        return $this->document->getType()->getValue() == DocumentType::CNPJ;
    }
    
    /**
     * @return DBint
     */
    public function getId(): DBint
    {
        return $this->id;
    }
    
    
    /**
     * @param array $data
     * @return static
     * @throws ReflectionException
     */
    public static function build(array $data): self
    {
        $id = new DBint($data['id']);
        $document = Document::build($data['document']['type'], $data['document']['identifier']);
        $email = new Email($data['email']);
        $name = new FullName($data['name']);
        
        return new static($id, $document, $email, $name);
    }
    
    public function toArray(): array
    {
        return [
          'id' => $this->id->getValue(),
          'document' => $this->document->toArray(),
          'email' => $this->email->getValue(),
          'name' => $this->name->getValue(),  
        ];
    }
}
