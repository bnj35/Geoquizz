<?php

use geoquizz\core\services\directus\DirectusInfoServiceInterface;
use geoquizz\core\services\directus\DirectusInfoService;
use geoquizz\application\actions\DisplayStatAction;
use geoquizz\application\actions\DisplayStatsAction;
use geoquizz\application\actions\UpdateScoreAction;
use geoquizz\application\actions\UpdateStatAction;
use geoquizz\core\repositoryInterfaces\StatsRepositoryInterface;
use geoquizz\core\services\stats\StatsService;
use geoquizz\core\services\stats\StatsServiceInterface;
use geoquizz\infrastructure\db\PDOStatsRepository;
use Psr\Container\ContainerInterface;
use geoquizz\infrastructure\db\PDOPartieRepository;
use geoquizz\core\repositoryInterfaces\PartieRepositoryInterface;
use geoquizz\core\services\partie\ServicePartieInterface;
use geoquizz\core\services\partie\ServicePartie;
use geoquizz\application\actions\CreatePartieAction;
use geoquizz\application\actions\CreateStatAction;
use geoquizz\application\actions\GetPartiesAction;
use geoquizz\application\actions\GetPartieByIdAction;
use geoquizz\application\actions\GetPartiesByUserAction;
use geoquizz\application\actions\ClosePartieAction;
use geoquizz\core\repositoryInterfaces\RepositoryAuthInterface;
use geoquizz\infrastructure\db\AuthRepositoryPDO;
use GuzzleHttp\Client;

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
                $c->get('log.prog.level')
            )
        );
        return $logger;
    },

    'pdo_partie' => function (ContainerInterface $c) {
        $data = parse_ini_file($c->get('partie.ini'));
        $pdo_partie = new PDO('pgsql:host=' . $data['host'] . ';dbname=' . $data['dbname'], $data['username'], $data['password']);
        $pdo_partie->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo_partie;
    },

    'pdo_users' => function (ContainerInterface $c) {
        $data = parse_ini_file(__DIR__ . '/users.ini');
        $pdo_users = new PDO('pgsql:host=' . $data['host'] . ';dbname=' . $data['dbname'], $data['username'], $data['password']);
        $pdo_users->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo_users;
    },

    //client directus
    'directus.client' => function (ContainerInterface $c) {
        return new Client([
            'base_uri' => 'http://directus:8055',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);
    },

    StatsRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOStatsRepository($c->get('pdo_partie'));
    },
    // Repositories

    PartieRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOPartieRepository($c->get('pdo_partie'));
    },

    RepositoryAuthInterface::class => function (ContainerInterface $c) {
        return new AuthRepositoryPDO($c->get('pdo_users'));
    },

    StatsServiceInterface::class => function (ContainerInterface $c) {
        return new StatsService($c->get(StatsRepositoryInterface::class));
    },

    // Services
    ServicePartieInterface::class => function (ContainerInterface $c) {
        return new ServicePartie(
            $c->get(PartieRepositoryInterface::class),
            $c->get(RepositoryAuthInterface::class)
        );
    },

    DirectusInfoServiceInterface::class => function (ContainerInterface $c) {
        return new DirectusInfoService(
            $c->get('directus.client')
        );
    },

    // Actions

    CreatePartieAction::class => function (ContainerInterface $c) {
        return new CreatePartieAction(
            $c->get(ServicePartieInterface::class),
            $c->get(DirectusInfoServiceInterface::class)

        );
    },

    CreateStatAction::class => function (ContainerInterface $c) {
        return new CreateStatAction($c->get(StatsServiceInterface::class));
    },

    DisplayStatAction::class => function (ContainerInterface $c) {
        return new DisplayStatAction($c->get(StatsServiceInterface::class));
    },

    GetPartiesAction::class => function (ContainerInterface $c) {
        return new GetPartiesAction(
            $c->get(ServicePartieInterface::class)
        );
    },
    DisplayStatsAction::class => function (ContainerInterface $c) {
        return new DisplayStatsAction($c->get(StatsServiceInterface::class));
    },

    GetPartieByIdAction::class => function (ContainerInterface $c) {
        return new GetPartieByIdAction(
            $c->get(ServicePartieInterface::class)
        );
    },
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
    },

    ClosePartieAction::class => function (ContainerInterface $c){
        return new ClosePartieAction(
            $c->get(ServicePartieInterface::class)
        );
    }


];
