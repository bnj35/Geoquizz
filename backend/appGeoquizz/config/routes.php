<?php
declare(strict_types=1);

use geoquizz\application\actions\CreatePartieAction;
use geoquizz\application\actions\GetPartiesAction;
use geoquizz\application\actions\GetPartieByIdAction;
use geoquizz\application\actions\GetPartiesByUserAction;

return function(\Slim\App $app): \Slim\App {

    $app->post('/parties', CreatePartieAction::class)->setName('createPartie');

    $app->get('/parties', GetPartiesAction::class)->setName('getParties');

    $app->get('/parties/{id}', GetPartieByIdAction::class)->setName('getPartieById');
    
    $app->get('/users/{id}/parties', GetPartiesByUserAction::class)->setName('getPartiesByUser');
    $app->get('/', function ($request, $response, $args) {
        $response->getBody()->write("Hello world!");
        return $response;
    });

    return $app;
};
