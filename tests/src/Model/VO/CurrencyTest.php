<?php

namespace Tests\Model\VO;


use App\Model\Exception\InvalidEnum;
use App\Model\VO\Currency;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{

    public function testValidValue()
    {
        $currency = new Currency('BRL');
        
        static::assertEquals('BRL', $currency);
    }

    public function testInvalidValue()
    {
        $this->expectException(InvalidEnum::class);
        
       new Currency('xablau');
    }
}
