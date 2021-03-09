<?php

namespace App\Model;

use ReflectionException;

final class Document
{
    use Immutability;
    
    private DocumentType $type;
    
    private StrValue $identification;
    
    public function __construct(DocumentType $type, StrValue $identification)
    {
        $this->type = $type;
        $this->identification = $identification;
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
    public function getIdentification(): StrValue
    {
        return $this->identification;
    }

    /**
     * @param $type
     * @param $identification
     * @return Document
     * @throws ReflectionException
     */
    public static function build($type, $identification): Document
    {
        $documentType = new DocumentType($type);
        $documentIdentification = new StrValue($identification);
        
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
          'type' => $this->type,
          'identification' => $this->identification  
        ];
    }
    
}
