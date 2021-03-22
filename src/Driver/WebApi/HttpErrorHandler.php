<?php

namespace App\Driver\WebApi;

use App\Driver\WebApi\Middleware\BadRequestException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpSpecializedException;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;

final class HttpErrorHandler implements ErrorHandlerInterface
{
    /**
     * @var CallableResolverInterface
     */
    private CallableResolverInterface $callableResolver;
    /**
     * @var ResponseFactoryInterface
     */
    private ResponseFactoryInterface $responseFactory;
    /**
     * @var LoggerInterface|null
     */
    private ?LoggerInterface $logger;

    /**
     * @param CallableResolverInterface $callableResolver
     * @param ResponseFactoryInterface  $responseFactory
     * @param LoggerInterface|null      $logger
     */
    public function __construct(
        CallableResolverInterface $callableResolver,
        ResponseFactoryInterface $responseFactory,
        ?LoggerInterface $logger = null
    ) {
        $this->callableResolver = $callableResolver;
        $this->responseFactory = $responseFactory;
        $this->logger = $logger;
    }
    
    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $payload = ['error' => 'internal_server_error'];

        $response = $this->responseFactory->createResponse();

        if ($exception instanceof HttpSpecializedException) {
            $response = $response->withStatus($exception->getCode());
            $payload = ['error' => $exception->getMessage()];
        }

        if ($exception instanceof BadRequestException) {
            $response = $response->withStatus($exception->getCode());

            $payload = ['error' => $exception->jsonSerialize()];
        }

        $response->getBody()->write(
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    }
}
