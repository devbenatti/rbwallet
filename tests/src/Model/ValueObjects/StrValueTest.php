<?php

namespace Tests\Model\ValueObjects;

use App\Model\VO\StrValue;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class StrValueTest extends TestCase
{
    
    public function testValidValue()
    {
        $str = new StrValue('testevalidstring');
        
        static::assertEquals('testevalidstring',$str->getValue());
    }
    
    public function testInvalidValue()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value need to be string');
        new StrValue(1);
    }

}
