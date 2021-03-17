<?php

namespace Tests\Model\VO;

use App\Model\VO\Cpf;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CpfTest extends TestCase
{

    /**
     * @dataProvider getValidData
     * @param $identification
     */
    public function testValidCPF($identification)
    {
        $cpf = new Cpf($identification);
        
        static::assertEquals($identification, $cpf->getValue());
    }

    /**
     * @dataProvider getInvalidData
     * @param $identification
     */
    public function testInvalidCPF($identification)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Invalid value for %s', $identification));
        
        new Cpf($identification);
    }

    /**
     * @return array
     */
    public function getValidData(): array
    {
        return [
            [
                '46381419008'
            ],
            [
                '546.233.410-97'
            ],
            [
                '45140949008'
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
                '46381429008'
            ],
            [
                ''
            ],
            [
                '1221215454'
            ],
            [
                '804.678.280-7522'
            ]
        ];
    }

}
