<?php

use App\Driver\WebApi\HttpErrorHandler;
use App\Driver\WebApi\Middleware\BadRequestException;
use DI\ContainerBuilder;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpSpecializedException;
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

$app->addRoutingMiddleware();

$myErrorHandler = new HttpErrorHandler($app->getCallableResolver(), $app->getResponseFactory());

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$errorMiddleware->setDefaultErrorHandler($myErrorHandler);

$app->run();
