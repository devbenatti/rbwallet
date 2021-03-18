<?php

namespace App\Driven\Database\DAO;

use Doctrine\DBAL\Connection;

trait DAOCapabilities
{
    private Connection $database;

    public function __construct(Connection $database)
    {
        $this->database = $database;
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
