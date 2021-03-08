<?php

use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([
        'settings' => [
            'component' => 'vegas-ws',
            'localEnvironment' => getenv('DOCKER_CONTAINER') === '1',
            'logger' => [
                'colorized'     => (bool)getenv('DOCKER_CONTAINER'),
                'customLogPath' => '/tmp/logfile'
            ],
            'externalComponents' => [
                'refund' => getenv('REFUND_URI'),
                'fallout' => getenv('FALLOUT_URI'),
                'vegasCheckout' => getenv('VEGAS_CHECKOUT_URI'),
                'falloutExternalUrl' => getenv('FALLOUT_EXTERNAL_URL'),
                'vegasCheckoutExternalUrl' => getenv('VEGAS_CHECKOUT_EXTERNAL_URL')
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
