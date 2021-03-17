<?php

namespace Tests\Model\VO;


use App\Model\VO\Decimal;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DecimalTest extends TestCase
{

    public function testValidDecimal()
    {
        $amount = new Decimal(200.00);
        
        self::assertEquals(200.00, $amount->getValue());
    }
    
    public function testInvalidDecimal()
    {
        $this->expectException(InvalidArgumentException::class);
        
        new Decimal('100');
    }
    
    public function testAddValue()
    {
        $amount = new Decimal(200.00);
        $amountToAdd = new Decimal(50.00);
        $newAmount = $amount->add($amountToAdd);
        
        static::assertEquals(250.00, $newAmount->getValue());
    }

    public function testSubValue()
    {
        $amount = new Decimal(200.00);
        $amountToSub = new Decimal(50.00);
        $newAmount = $amount->sub($amountToSub);

        static::assertEquals(150.00, $newAmount->getValue());
    }
}
