<?php

namespace App\Driven\Database\DAO;

use Doctrine\DBAL\Connection;
use Psr\Cache\CacheItemPoolInterface;

trait DAOCapabilities
{
    private Connection $database;
    private CacheItemPoolInterface $cache;

    public function __construct(Connection $database, CacheItemPoolInterface $cache)
    {
        $this->database = $database;
        $this->cache = $cache;
    }

    public function beginTransaction(): void
    {
        $this->database->beginTransaction();
    }
    
    public function rollBack(): void
    {
        $this->database->rollBack();
    }
    
    public function commit(): void
    {
        $this->database->commit();
    }
}
