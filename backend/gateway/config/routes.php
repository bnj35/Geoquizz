<?php
declare(strict_types=1);

use Slim\App;
use geoquizz\application\actions\GatewayPlayerAction;

return function(App $app):App {


    //micro-service geoquizz

    $app->get('/stats[/]', GatewayPlayerAction::class);
    $app->get('/stats/{id}[/]', GatewayPlayerAction::class);
    $app->post('/stats[/]', GatewayPlayerAction::class);
    $app->put('/stats/{id}[/]', GatewayPlayerAction::class);

    $app->post('/parties', GatewayPlayerAction::class)->setName('createPartie');

    $app->get('/parties', GatewayPlayerAction::class)->setName('getParties');

    $app->get('/parties/{id}', GatewayPlayerAction::class)->setName('getPartieById');

    $app->get('/users/{id}/parties', GatewayPlayerAction::class)->setName('getPartiesByUser');

    $app->patch('/parties/{id}/score', GatewayPlayerAction::class)->setName('updateScore');

    $app->patch('/parties/{id}/done', GatewayPlayerAction::class)->setName('closePartie');

    return $app;
};
