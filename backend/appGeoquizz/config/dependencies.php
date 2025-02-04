<?php

use geoquizz\application\actions\CreateStatAction;
use geoquizz\application\actions\DisplayStatAction;
use geoquizz\application\actions\DisplayStatsAction;
use geoquizz\application\actions\UpdateStatAction;
use geoquizz\core\repositoryInterfaces\StatsRepositoryInterface;
use geoquizz\core\services\stats\StatsService;
use geoquizz\core\services\stats\StatsServiceInterface;
use geoquizz\infrastructure\db\PDOStatsRepository;
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
use geoquizz\application\actions\UpdatePartieAction;

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

    StatsRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOStatsRepository($c->get('pdo_partie'));
    },
    // Repositories

    PartieRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOPartieRepository($c->get('pdo_partie'));
    },
    StatsServiceInterface::class => function (ContainerInterface $c) {
        return new StatsService($c->get(StatsRepositoryInterface::class));
    },

    // Services
    ServicePartieInterface::class => function (ContainerInterface $c) {
        return new ServicePartie(
            $c->get(PartieRepositoryInterface::class)
        );
    CreateStatAction::class => function (ContainerInterface $c) {
        return new CreateStatAction($c->get(StatsServiceInterface::class));
    },

    // Actions

    CreatePartieAction::class => function (ContainerInterface $c) {
        return new CreatePartieAction(
            $c->get(ServicePartieInterface::class)
        );
    DisplayStatAction::class => function (ContainerInterface $c) {
        return new DisplayStatAction($c->get(StatsServiceInterface::class));
    },

    GetPartiesAction::class => function (ContainerInterface $c) {
        return new GetPartiesAction(
            $c->get(ServicePartieInterface::class)
        );
    DisplayStatsAction::class => function (ContainerInterface $c) {
        return new DisplayStatsAction($c->get(StatsServiceInterface::class));
    },

    GetPartieByIdAction::class => function (ContainerInterface $c) {
        return new GetPartieByIdAction(
            $c->get(ServicePartieInterface::class)
        );
    UpdateStatAction::class => function (ContainerInterface $c) {
        return new UpdateStatAction($c->get(StatsServiceInterface::class));
    },

    GetPartiesByUserAction::class => function (ContainerInterface $c) {
        return new GetPartiesByUserAction(
            $c->get(ServicePartieInterface::class)
        );
    },

    UpdateScoreAction::class => function (ContainerInterface $c) {
        return new UpdateScoreAction(
            $c->get(ServicePartieInterface::class)
        );
    }


];
