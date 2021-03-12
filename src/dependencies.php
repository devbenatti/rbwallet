<?php

use App\Command\Transaction\TransactionHandler;
use App\Driven\Database\DAO\PersonDAO;
use App\Driven\Database\Repository\MysqlWalletRepository;
use App\Driver\API\Action\TransactionAction;
use App\Model\Wallet\WalletRepository;
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
        PersonDAO::class => function (ContainerInterface $c) {
            return new PersonDAO($c->get(Connection::class));
        },
        TransactionHandler::class => function (ContainerInterface $c) {
            return new TransactionHandler($c->get(PersonDAO::class));
        },
        TransactionAction::class => function (ContainerInterface $c) {
            return new TransactionAction($c->get(TransactionHandler::class));
        },
        WalletRepository::class => function (ContainerInterface $c) {
            return new MysqlWalletRepository($c->get(Connection::class));
        }
    ]);
};
