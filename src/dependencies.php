<?php

use DI\ContainerBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        Connection::class => function (ContainerInterface $c) {
            $settings = [
                'host'          => $c->get('settings')['mysql']['host'],
                'dbname'        => $c->get('settings')['mysql']['database'],
                'user'          => $c->get('settings')['mysql']['user'],
                'password'      => $c->get('settings')['mysql']['pass'],
                'port'          => 3306,
                'driver'        => 'pdo_mysql',
                'charset'       => 'utf8'
            ];

            return DriverManager::getConnection($settings);
        },
    ]);
};
