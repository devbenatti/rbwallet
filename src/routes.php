<?php

use App\Driver\WebApi\Action\TransactionAction;
use App\Driver\API\Middleware\AuthorizationTransaction;
use Slim\App;

return function (App $app) {
    $app->post('/transaction', TransactionAction::class)
        ->add(AuthorizationTransaction::class);
};
