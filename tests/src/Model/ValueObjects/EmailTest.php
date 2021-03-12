<?php

namespace Tests\Model\ValueObjects;


use App\Model\VO\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    
    public function testValidEmail()
    {
        $email = new Email('teste@gmail.com');
        
        static::assertEquals('teste@gmail.com', $email->getValue());
    }
    
    public function testInvalidEmail()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid value for Email');
        
        new Email('xablau.com.br');
    }

}
