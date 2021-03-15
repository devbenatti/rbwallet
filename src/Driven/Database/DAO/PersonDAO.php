<?php

namespace App\Driven\Database\DAO;

use App\Model\Wallet\Person;
use App\Model\VO\DocumentType;
use ReflectionException;

/**
 * Class PersonDAO
 * @package App\Driven\Database\DAO
 */
final class PersonDAO
{
    use DAOCapabilities;

    /**
     * @param int $id
     * @return Person|null
     * @throws ReflectionException
     */
    public function getById(int $id): ?Person
    {
        return Person::build([
            'id' => 1,
            'email' => 'xablau@gmail.com',
            'document' => [
                'type' => DocumentType::CPF,
                'identifier' => '09448045690'
            ],
            'name' => 'Xablau Teste'
        ]);
    }
}
