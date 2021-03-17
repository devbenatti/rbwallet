<?php

use App\Command\Transaction\TransactionHandler;
use App\Driven\Database\DAO\PersonDAO;
use App\Driven\Database\DAO\TransactionDAO;
use App\Driven\Database\Repository\MysqlWalletRepository;
use App\Driven\Http\Authorizer;
use App\Driven\Http\TransactionAuthorizer;
use App\Driven\Uuid\UuidAdapter;
use App\Driven\Uuid\UuidGenerator;
use App\Driver\WebApi\Action\TransactionAction;
use App\Driver\WebApi\Validator\JsonSchemaValidator;
use App\Driver\WebApi\Validator\Validator;
use App\Model\WalletRepository;
use DI\ContainerBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;

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
        WalletRepository::class => function (ContainerInterface $c) {
            return new MysqlWalletRepository($c->get(Connection::class));
        },
        TransactionDAO::class => function (ContainerInterface $c) {
            return new TransactionDAO($c->get(Connection::class));
        },
        UuidGenerator::class => function () {
            return new UuidAdapter();
        },
        ClientInterface::class => function () {
            return new Client();
        },
        TransactionAuthorizer::class => function (ContainerInterface $c) {
            return new Authorizer($c->get(ClientInterface::class));
        },
        TransactionHandler::class => function (ContainerInterface $c) {
            return new TransactionHandler(
                $c->get(WalletRepository::class),
                $c->get(TransactionDAO::class),
                $c->get(TransactionAuthorizer::class)
            );
        },
        TransactionAction::class => function (ContainerInterface $c) {
            return new TransactionAction($c->get(TransactionHandler::class),$c->get(UuidGenerator::class));
        },
        Validator::class => function () {
            return new JsonSchemaValidator();
        }
    ]);
};
