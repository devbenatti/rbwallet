<?php

namespace Tests\Model;

use App\Model\Person\User;
use App\Model\ValueObjects\DocumentType;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testValidUser()
    {
        $userData = $this->getUserData();
        
        $user = User::build($userData);
        
        static::assertEquals($userData, $user->toArray());
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
                    'identifier' => '02349057640'
                ],
                'email' => 'test@gmail.com',
                'password' => 'semsenha'
            ], $data)
        );
    }
}
