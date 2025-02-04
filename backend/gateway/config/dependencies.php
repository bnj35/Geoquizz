<?php

use Psr\Container\ContainerInterface;
use geoquizz\application\actions\GatewayAuthAction;
use geoquizz\core\services\auth\ServiceAuthentificationInterface;
use GuzzleHttp\Client;
use geoquizz\application\middleware\AuthMiddleware;
use geoquizz\application\actions\GatewayPlayerAction;

return [

    //log 
    'log.prog.level' => \Monolog\Level::Debug,
    'log.prog.name' => 'geoquizz.program.log',
    'log.prog.file' => __DIR__ . '/log/geoquizz.program.error.log',
    'prog.logger' => function (ContainerInterface $c) {
        $logger = new \Monolog\Logger($c->get('log.prog.name'));
        $logger->pushHandler(
            new \Monolog\Handler\StreamHandler(
                $c->get('log.prog.file'),
                $c->get('log.prog.level')));
        return $logger;
    },

    // Guzzle clients for microservices
    'player.client' => function (ContainerInterface $c) {
        return new Client(['base_uri' => 'http://api.services.geoquizz/']);
    },
    'auth.client' => function (ContainerInterface $c) {
        return new Client(['base_uri' => 'http://api.auth.geoquizz/']);
    },

  // Middleware
    AuthMiddleware::class => function (ContainerInterface $c) {
    return new AuthMiddleware($c->get('auth.client'));
    },

    //Actions

    //player
    GatewayPlayerAction::class => function(ContainerInterface $c) {
        return new GatewayPlayerAction($c->get('player.client'));
    },

    //auth
    GatewayAuthAction::class => function(ContainerInterface $c) {
        return new GatewayAuthAction($c->get('auth.client'));
    },

];
