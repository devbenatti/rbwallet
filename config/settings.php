<?php

use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([
        'settings' => [
            'component' => '',
            'localEnvironment' => getenv('DOCKER_CONTAINER') === '1',
            'logger' => [
                'colorized'     => (bool)getenv('DOCKER_CONTAINER'),
                'customLogPath' => '/tmp/logfile'
            ],
            'mysql' => [
                'host'      => getenv('DATABASE_HOST'),
                'user'      => getenv('DATABASE_USER'),
                'pass'      => getenv('DATABASE_PASS'),
                'database'  => getenv('DATABASE_NAME'),
                'aes_key'   => getenv('DATABASE_AES_KEY')
            ]
        ],
    ]);
};
