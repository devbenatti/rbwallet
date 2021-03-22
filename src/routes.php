<?php

use App\Driver\WebApi\Action\CreateAction;
use App\Driver\WebApi\Action\TransactionAction;
use App\Driver\WebApi\Middleware\AuthorizationTransaction;
use App\Driver\WebApi\Middleware\PayloadValidator;
use App\Driver\WebApi\Middleware\PreventDuplicatedUser;
use Slim\App;

return function (App $app) {
    $app->post('/transaction', TransactionAction::class)
        ->add(AuthorizationTransaction::class)
        ->add(PayloadValidator::class);
    
    $app->post('/create', CreateAction::class)
        ->add(PreventDuplicatedUser::class)
        ->add(PayloadValidator::class);
};
