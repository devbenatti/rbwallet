<?php

namespace App\Model\Person;

use App\Model\Immutability;
use App\Model\ValueObjects\Document;
use App\Model\ValueObjects\Email;
use App\Model\ValueObjects\FullName;
use App\Model\ValueObjects\Uuid;
use ReflectionException;

trait PersonCapabilities
{
    use Immutability;

    private Uuid $id;

    private Document $document;
    
    private Email $email;
    
    private FullName $name;


    /**
     * Payer constructor.
     * @param Uuid $id
     * @param Document $document
     * @param Email $email
     * @param FullName $name
     */
    public function __construct(Uuid $id, Document $document, Email $email, FullName $name)
    {
        $this->id = $id;
        $this->document = $document;
        $this->email = $email;
        $this->name = $name;
    }


    public function getDocument(): Document
    {
        return $this->document;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
    
    public function getEmail(): Email
    {
        return $this->email;
    }
    
    public function getName(): FullName
    {
        return $this->name;
    }
    
    /**
     * @param array $data
     * @return static
     * @throws ReflectionException
     */
    public static function build(array $data): self
    {
        $id = new Uuid($data['id']);
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
