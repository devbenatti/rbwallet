<?php

namespace Tests\Model\ValueObjects;


use App\Model\Exception\InvalidEnum;
use App\Model\ValueObjects\Currency;
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
