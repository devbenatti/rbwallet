<?php

namespace App\Driven\Database\DAO;

use App\Model\User;
use App\Model\Person;
use Doctrine\DBAL\Exception;
use ReflectionException;

/**
 * Class PersonDAO
 * @package App\Driven\Database\DAO
 */
final class PersonDAO
{
    use DAOCapabilities;
    
    public function create(User $user): int
    {
        $this->database->insert('user', [
            'document' => $user->getDocument()->getIdentifier(),
            'name' => $user->getName()->getValue(),
            'email' => $user->getEmail()->getValue(),
            'password' => $user->getPassword()->getValue()
        ]);
        
        return (int) $this->database->lastInsertId();
    }

    /**
     * @param int $id
     * @return Person|null
     * @throws ReflectionException|Exception
     */
    public function getById(int $id): ?Person
    {
        $data = $this->database
            ->createQueryBuilder()
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
            'document' => $data['document'],
            'name' => $data['name']
        ]);
        
        return $payer;
    }

    /**
     * @param string $email
     * @param string $document
     * @return Person|null
     * @throws Exception|ReflectionException
     */
    public function getByEmailOrDocument(string $email, string $document): ?Person
    {
        $data = $this->database
            ->createQueryBuilder()
            ->select([
                'u.id',
                'u.document',
                'u.email',
                'u.name'
            ])
            ->from('user', 'u')
            ->where('u.document = :document')
            ->orWhere('u.email = :email')
            ->setParameter('document', $document)
            ->setParameter('email', $email)
            ->execute()
            ->fetchAssociative();
        
        if (!$data) {
            return null;
        }

        $user = Person::build([
            'id' => (int)$data['id'],
            'email' => $data['email'],
            'document' => $data['document'],
            'name' => $data['name']
        ]);

        return $user;
    }
}
