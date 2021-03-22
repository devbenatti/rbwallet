<?php

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

// Define Custom Error Handler
$customErrorHandler = function (
    ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails,
    ?LoggerInterface $logger = null
) use ($app) {
    $payload = ['error' => 'internal_server_error'];

    $response = $app->getResponseFactory()->createResponse();
    
    if ($exception instanceof HttpSpecializedException) {
        $response = $response->withStatus($exception->getCode());
        $payload = ['error' => $exception->getMessage()];
    }
    
    if ($exception instanceof BadRequestException) {
        $response = $response->withStatus($exception->getCode());
        
        $payload = $exception->jsonSerialize();
    }
    
    $response->getBody()->write(
        json_encode($payload, JSON_UNESCAPED_UNICODE)
    );

    return $response->withHeader(
            'Content-Type',
            'application/json'
        );
};

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->run();
