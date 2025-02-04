<?php

use Psr\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use geoquizz\infrastructure\db\PDOPartieRepository;
use geoquizz\core\repositoryInterfaces\PartieRepositoryInterface;
use geoquizz\core\services\partie\ServicePartieInterface;
use geoquizz\core\services\partie\ServicePartie;
use geoquizz\application\actions\CreatePartieAction;
use geoquizz\application\actions\GetPartiesAction;
use geoquizz\application\actions\GetPartieByIdAction;
use geoquizz\application\actions\GetPartiesByUserAction;

return [

    // Logger
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

    'pdo_partie' => function (ContainerInterface $c){
        $data = parse_ini_file($c->get('partie.ini'));
        $pdo_partie = new PDO('pgsql:host='.$data['host'].';dbname='.$data['dbname'], $data['username'], $data['password']);
        $pdo_partie->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo_partie;
    },

    // Repositories

    PartieRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOPartieRepository($c->get('pdo_partie'));
    },



    // Providers

    // Services
    PartieServiceInterface::class => function (ContainerInterface $c) {
        return new ServicePartie(
            $c->get(PartieRepositoryInterface::class)
        );
    },

    // Actions

    CreatePartieAction::class => function (ContainerInterface $c) {
        return new CreatePartieAction(
            $c->get(PartieServiceInterface::class)
        );
    },

    GetPartiesAction::class => function (ContainerInterface $c) {
        return new GetPartiesAction(
            $c->get(PartieServiceInterface::class)
        );
    },

    GetPartieByIdAction::class => function (ContainerInterface $c) {
        return new GetPartieByIdAction(
            $c->get(PartieServiceInterface::class)
        );
    },

    GetPartiesByUserAction::class => function (ContainerInterface $c) {
        return new GetPartiesByUserAction(
            $c->get(PartieServiceInterface::class)
        );
    },
    
];
