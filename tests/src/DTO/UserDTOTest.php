<?php

namespace Tests\DTO;

use App\DTO\UserDTO;
use App\Model\VO\DocumentType;
use PHPUnit\Framework\TestCase;

class UserDTOTest extends TestCase
{
    public function testValidUser()
    {
        $userData = $this->getUserData();
        
        $user = UserDTO::build($userData);
        
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
