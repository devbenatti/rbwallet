<?php

namespace Tests\Model;

use App\Model\Document;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{

    public function testValidDocument()
    {
        $document = Document::build('CPF', '07749667910');
        
        static::assertEquals('07749667910', $document->getIdentification()->getValue());
        static::assertEquals('CPF', $document->getType()->getValue());
    }
    
    public function testInvalidDocumentType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid ENUM for value RG');
        
        Document::build('RG', '07749667910');
    }
    
    public function testInvalidDocumentIdentification()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value need to be string');

        Document::build('CPF', 132);
    }
    
    public function testValidDocumentShouldBeAnCPF()
    {
        $document = Document::build('CPF', '07749667910');
        
        static::assertTrue($document->isCPF());
    }
}
