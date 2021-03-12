<?php

namespace App\Driven\Database\Repository;

use Doctrine\DBAL\Connection;

trait RepositoryCapabilities
{
    private Connection $database;

    public function __construct(Connection $database)
    {
        $this->database = $database;
    }
}
