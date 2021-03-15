<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

$containerBuilder = new ContainerBuilder();

$settings = require __DIR__ . '/../config/settings.php';
$settings($containerBuilder);

$dependencies = require __DIR__ . '/../src/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

$app->addBodyParsingMiddleware();
// Add Routing Middleware
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->run();
