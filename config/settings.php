<?php

use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([
        'settings' => [
            'component' => '',
            'localEnvironment' => getenv('DOCKER_CONTAINER') === '1',
            'mysql' => [
                'host'      => getenv('DATABASE_HOST'),
                'user'      => getenv('DATABASE_USER'),
                'pass'      => getenv('DATABASE_PASS'),
                'database'  => getenv('DATABASE_NAME'),
            ],
            'cache' => [
                'host' => getenv('CACHE_HOST'),
            ]
        ],
    ]);
};
