<?php

use App\Driver\API\Action\TransactionAction;
use Slim\App;

return function (App $app) {
    $app->get('/', TransactionAction::class);
};
