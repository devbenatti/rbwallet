<?php

use App\Driver\WebApi\Action\TransactionAction;
use App\Driver\WebApi\Middleware\AuthorizationTransaction;
use App\Driver\WebApi\Middleware\PayloadValidator;
use Slim\App;

return function (App $app) {
    $app->post('/transaction', TransactionAction::class)
        ->add(PayloadValidator::class)
        ->add(AuthorizationTransaction::class);
};
