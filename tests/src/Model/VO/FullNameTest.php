<?php

namespace Tests\Model\VO;

use App\Model\VO\FullName;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class FullNameTest extends TestCase
{

    /**
     * @dataProvider getValidData
     * @param $data
     */
    public function testValidFullName($data)
    {
        $name = new FullName($data);
        
        static::assertEquals($data, $name->getValue());
        
    }

    /**
     * @dataProvider getInvalidData
     * @param $data
     */
    public function testInvalidFullName($data)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid full name');
        new FullName($data);
        
    }

    /**
     * @return array
     */
    public function getValidData(): array
    {
        return [
            [
                'Renato Benatti',
                'João das Neves',
                'José da Silva'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getInvalidData(): array
    {
        return [
            [
                'Renato',
                'João',
                '123',
                '',
                'ahsh112'
            ]
        ];
    }
}
