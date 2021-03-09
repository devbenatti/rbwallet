<?php

namespace Tests\Model;

use App\Model\DocumentType;
use App\Model\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testValidUser()
    {
        $userData = $this->getUserData();
        
        $user = User::build($userData);
        
        static::assertEquals($userData, $user->toArray());
    }
    
    public function testIsMerchantShouldBeTrue()
    {
        $userData = $this->getUserData([
            'document' => [
                'type' => DocumentType::CNPJ,
                'identification' => '99464576000177'
            ]
        ]);

        $user = User::build($userData);

        static::assertEquals($userData, $user->toArray());
        static::assertTrue($user->isMerchant());
    }

    /**
     * @param array $data
     * @return array
     */
    private function getUserData(array $data = [])
    {
        return array_filter(
            array_merge([
                'name' => 'Renato Benatti',
                'document' => [
                    'type' => DocumentType::CPF,
                    'identification' => '02349057640'
                ],
                'email' => 'test@gmail.com',
                'password' => 'semsenha'
            ], $data)
        );
    }

}
