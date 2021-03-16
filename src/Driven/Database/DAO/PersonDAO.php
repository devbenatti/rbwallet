<?php

namespace App\Driven\Database\DAO;

use App\Model\VO\Cpf;
use App\Model\Wallet\Person;
use App\Model\VO\DocumentType;
use Doctrine\DBAL\Exception;
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
     * @throws ReflectionException|Exception
     */
    public function getById(int $id): ?Person
    {
        $data = $this->getDatabase()->createQueryBuilder()
            ->select([
                'u.id',
                'u.document',
                'u.email',
                'u.name'
            ])
            ->from('user', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->execute()
            ->fetchAssociative();
        
        if (!$data) {
            return null;
        }
        
        $payer = Person::build([
            'id' => (int)$data['id'],
            'email' => $data['email'],
            'document' => [
                'type' => strlen($data['document']) == Cpf::CPF_LENGTH ? DocumentType::CPF : DocumentType::CNPJ,
                'identifier' => $data['document']
            ],
            'name' => $data['name']
        ]);
        
        return $payer;
    }
}
