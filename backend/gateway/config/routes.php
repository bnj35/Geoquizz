<?php
declare(strict_types=1);

use geoquizz\application\actions\GatewayAuthAction;
use geoquizz\application\actions\GatewayDirectusAction;
use geoquizz\application\middlewares\Auth;
use Slim\App;
use geoquizz\application\actions\GatewayPlayerAction;
use geoquizz\application\actions\GatewayAssetsAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

return function(App $app):App {


    //micro-service geoquizz

    $app->options('/{routes:.+}', function (Request $rq, Response $rs, array $args): Response {
        return $rs;
    });

    $app->get('/stats[/]', GatewayPlayerAction::class);
    $app->get('/stats/{id}[/]', GatewayPlayerAction::class);
    $app->post('/stats[/]', GatewayPlayerAction::class);
    $app->put('/stats/{id}[/]', GatewayPlayerAction::class);

    $app->post('/parties[/]', GatewayPlayerAction::class)->setName('createPartie')
        ->add(Auth::class);

    $app->get('/parties[/]', GatewayPlayerAction::class)->setName('getParties');

    $app->get('/parties/{id}[/]', GatewayPlayerAction::class)->setName('getPartieById');

    $app->get('/users/{id}/parties[/]', GatewayPlayerAction::class)->setName('getPartiesByUser')
        ->add(Auth::class);

    $app->patch('/parties/{id}/score[/]', GatewayPlayerAction::class)->setName('updateScore');

    $app->patch('/parties/{id}/done[/]', GatewayPlayerAction::class)->setName('closePartie');

    $app->get('/refresh[/]', GatewayAuthAction::class)->setName('refresh');
    $app->post('/signin[/]', GatewayAuthAction::class)->setName('signin');
    $app->post('/signup[/]', GatewayAuthAction::class)->setName('signup');
    $app->post('/validate[/]', GatewayAuthAction::class)->setName('validate');
    $app->get('/users[/]', GatewayAuthAction::class)->setName('users');
    $app->get('/users/{id}[/]', GatewayAuthAction::class)->setName('user');
    $app->get('/users/{id}/stats[/]', GatewayPlayerAction::class)->setName('getStatsByUser')
        ->add(Auth::class);

    $app->get('/assets/{id}',GatewayAssetsAction::class)->setName('getAssets');
    $app->get('/items/series', GatewayDirectusAction::class)->setName('getSeries');

    return $app;
};
