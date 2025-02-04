<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use GuzzleHttp\Client;
use geoquizz\application\actions\GatewayPlayerAction;

return function(App $app):App {

    $app->get('/', \geoquizz\application\actions\HomeAction::class);

    //micro-service geoquiee

    $app->post('/parties', GatewayPlayerAction::class)->setName('createPartie');

    $app->get('/parties', GatewayPlayerAction::class)->setName('getParties');

    $app->get('/parties/{id}', GatewayPlayerAction::class)->setName('getPartieById');
    
    $app->get('/users/{id}/parties', GatewayPlayerAction::class)->setName('getPartiesByUser');

    $app->patch('/parties/{id}/score', GatewayPlayerAction::class)->setName('updateScore');

    return $app;
};