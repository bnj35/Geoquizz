<?php
declare(strict_types=1);

use geoquizz\src\Action\CreatePartieAction;
use geoquizz\src\Action\GetPartiesAction;
use geoquizz\src\Action\GetPartieByIdAction;
use geoquizz\src\Action\GetPartiesByUserAction;



return function(\Slim\App $app):\Slim\App {

    $app->post('/parties', CreatePartieAction::class);
    ->setName('createPartie');

    $app->get('/parties', GetPartiesAction::class);
    $app->setName('getParties');

    $app->get('/parties/{id}', GetPartieByIdAction::class);
    $app->setName('getPartieById');
    
    $app->get('users/{id}/parties', GetPartiesByUserAction::class);
    $app->setName('getPartiesByUser');



    return $app;
};
