<?php

namespace App\Driven\Database\DAO;

use App\Model\User;
use App\Model\Person;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Psr\Cache\InvalidArgumentException;
use ReflectionException;

/**
 * Class PersonDAO
 * @package App\Driven\Database\DAO
 */
final class PersonDAO
{
    use DAOCapabilities;

    /** @var string */
    private const CACHE_TTL = '+5 minutes';
    
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
     * @throws ReflectionException|Exception|InvalidArgumentException
     */
    public function getById(int $id): ?Person
    {
        $cachedPerson = $this->getFromCache($id);
        
        if ($cachedPerson) {
            return $cachedPerson;
        }

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
        
        $this->saveInCache($payer);
        
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

    /**
     * @param Person $person
     * @throws InvalidArgumentException
     */
    private function saveInCache(Person $person): void
    {
        $item = $this->cache->getItem((string)$person->getId()->getValue());
        $item->set($person);
        $item->expiresAt(new DateTimeImmutable(self::CACHE_TTL));
        $this->cache->save($item);
    }

    /**
     * @param int $id
     * @return Person|null
     * @throws InvalidArgumentException
     */
    private function getFromCache(int $id): ?Person
    {
        $item = $this->cache->getItem((string)$id);
        return $item->isHit() ? $item->get() : null;
    }
}
