<?php

use Psr\Container\ContainerInterface;
use geoquizz\application\actions\GatewayAuthAction;
use geoquizz\application\middlewares\Auth;
use GuzzleHttp\Client;
use geoquizz\application\actions\GatewayPlayerAction;
use geoquizz\application\actions\GatewayAssetsAction;

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
                $c->get('log.prog.level')
            )
        );
        return $logger;
    },

    // Guzzle clients for microservices
    'player.client' => function (ContainerInterface $c) {
        return new Client(['base_uri' => 'http://api.services.geoquizz/']);
    },
    'auth.client' => function (ContainerInterface $c) {
        return new Client(['base_uri' => 'http://api.auth.geoquizz/']);
    },
    'directus.client' => function (ContainerInterface $c){
        return new Client(['base_uri' => 'http://directus:8055/']);
    },

    // Middleware
    Auth::class => function (ContainerInterface $c) {
        return new Auth($c->get('auth.client'));
    },

    //Actions

    //player
    GatewayPlayerAction::class => function (ContainerInterface $c) {
        return new GatewayPlayerAction($c->get('player.client'));
    },

    //auth
    GatewayAuthAction::class => function (ContainerInterface $c) {
        return new GatewayAuthAction($c->get('auth.client'));
    },

    //directus
    GatewayAssetsAction::class => function (ContainerInterface $c) {
        return new GatewayAssetsAction($c->get('directus.client'));
    },

];
