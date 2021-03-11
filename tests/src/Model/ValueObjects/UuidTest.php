<?php

namespace Tests\Model\ValueObjects;

use App\Model\ValueObjects\Uuid;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{

    /**
     * @dataProvider getValidData
     * @param string $value
     */
    public function testValidUuid(string $value)
    {
        $uuid = new Uuid($value);
        
        static::assertEquals($value, $uuid->getValue());
    }
    
    public function getValidData(): array
    {
        return [
            [
                '4641c676-570c-4b43-b4ff-9e94a54681cd'
            ],
            [
                '04009869-f342-499a-9699-f59e5b5c0492'
            ]
        ];
    }
}
