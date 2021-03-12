<?php

namespace App\Driven\Database\DAO;

use App\Model\Person\Payer;
use App\Model\Person\Person;
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
     * @return Person
     * @throws ReflectionException
     */
    public function getById(int $id): Person
    {
        return Payer::build([
            'id' => '5f63d951-5439-444b-9a05-e29d80b85da5',
            'email' => 'renato.benatti@gmail.com',
            'document' => [
                'type' => DocumentType::CPF,
                'identifier' => '09749067940'
            ],
            'name' => 'Renato Benatti'
        ]);
    }
}
