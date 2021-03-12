<?php

namespace App\Model\VO;

use App\Model\ImmutableCapabilities;
use ReflectionException;

final class Document
{
    use ImmutableCapabilities;
    
    private DocumentType $type;
    
    private StrValue $identifier;
    
    public function __construct(DocumentType $type, StrValue $identifier)
    {
        $this->type = $type;
        $this->identifier = $identifier;
    }
    
    /**
     * @return DocumentType
     */
    public function getType(): DocumentType
    {
        return $this->type;
    }

    /**
     * @return StrValue
     */
    public function getIdentifier(): StrValue
    {
        return $this->identifier;
    }

    /**
     * @param $type
     * @param $identifier
     * @return Document
     * @throws ReflectionException
     */
    public static function build($type, $identifier): Document
    {
        $documentType = new DocumentType($type);
        $documentIdentification = new StrValue($identifier);
        
        return new static($documentType, $documentIdentification);
    }

    /**
     * @return bool
     */
    public function isCPF(): bool
    {
        return $this->getType()->valueEquals(DocumentType::CPF);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
          'type' => $this->type->getValue(),
          'identifier' => $this->identifier->getValue()  
        ];
    }
    
}
