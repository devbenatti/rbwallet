<?php

namespace Tests\Model\VO;


use App\Model\VO\Document;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{

    public function testValidDocument()
    {
        $document = Document::build('28711186046');
        
        static::assertEquals('28711186046', $document->getIdentifier()->getValue());
        static::assertEquals('CPF', $document->getType()->getValue());
    }
    
    public function testInvalidDocumentIdentification()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value need to be string');

        Document::build(132);
    }
    
    public function testValidDocumentShouldBeAnCPF()
    {
        $document = Document::build('28711186046');
        
        static::assertTrue($document->isCPF());
    }
}
