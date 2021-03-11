<?php

use App\Command\Transaction\TransactionAction;
use Slim\App;

return function (App $app) {
    $app->get('/', TransactionAction::class);
};
