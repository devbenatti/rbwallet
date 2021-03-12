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
}
