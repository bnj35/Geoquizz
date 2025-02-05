<?php


use geoquizz\application\actions\DisplayAllUsersAction;
use geoquizz\application\actions\DisplayUserByIdAction;
use geoquizz\application\actions\RefreshAction;
use geoquizz\application\actions\SigninAction;
use geoquizz\application\actions\SignupAction;
use geoquizz\application\actions\ValidateAction;
use geoquizz\application\provider\auth\AuthProviderInterface;
use geoquizz\application\provider\auth\JWTAuthProvider;
use geoquizz\application\provider\auth\JWTManager;
use geoquizz\core\repositoryInterfaces\AuthRepositoryInterface;
use geoquizz\core\services\auth\AuthentificationService;
use geoquizz\core\services\auth\AuthentificationServiceInterface;
use geoquizz\core\services\stats\StatsServiceInterface;
use geoquizz\infrastructure\db\PDOAuthRepository;
use geoquizz\infrastructure\http\StatsServiceHTTP;
use Psr\Container\ContainerInterface;

return [

    // Logger
    'log.prog.level' => \Monolog\Level::Debug,
    'log.prog.name' => 'njp.program.log',
    'log.prog.file' => __DIR__ . '/log/njp.program.error.log',
    'prog.logger' => function (ContainerInterface $c) {
        $logger = new \Monolog\Logger($c->get('log.prog.name'));
        $logger->pushHandler(
            new \Monolog\Handler\StreamHandler(
                $c->get('log.prog.file'),
                $c->get('log.prog.level')));
        return $logger;
    },

    'pdo_auth' => function (ContainerInterface $c) {
        $data = parse_ini_file($c->get('auth.ini'));
        $pdo_auth = new PDO('pgsql:host='.$data['host'].';dbname='.$data['dbname'], $data['username'], $data['password']);
        $pdo_auth->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo_auth;
    },

    'client_stats' => function (ContainerInterface $c){
        return new \GuzzleHttp\Client([
            'base_uri' => 'http://api.services.geoquizz',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);
    },

    StatsServiceInterface::class => function (ContainerInterface $c) {
        return new StatsServiceHTTP($c->get('client_stats'));
    },

    // Repositories
    AuthRepositoryInterface::class => function (ContainerInterface $c) {
        return new PDOAuthRepository($c->get('pdo_auth'));
    },

    // Providers
    AuthProviderInterface::class => function (ContainerInterface $c) {
        return new JWTAuthProvider(
            $c->get(AuthentificationServiceInterface::class),
            new JWTManager
        );
    },

    // Services
    AuthentificationServiceInterface::class => function (ContainerInterface $c) {
        return new AuthentificationService(
            $c->get(AuthRepositoryInterface::class),
            $c->get(StatsServiceInterface::class)
        );
    },

    // Actions

    SigninAction::class => function (ContainerInterface $c) {
        return new SigninAction(
            $c->get(AuthProviderInterface::class)
        );
    },
    RefreshAction::class => function (ContainerInterface $c) {
        return new RefreshAction(
            $c->get(AuthentificationServiceInterface::class)
        );
    },
    ValidateAction::class => function (ContainerInterface $c) {
        return new ValidateAction(
            $c->get(AuthProviderInterface::class)
        );
    },
    SignupAction::class => function (ContainerInterface $c) {
        return new SignupAction(
            $c->get(AuthProviderInterface::class)
        );
    },
    DisplayAllUsersAction::class => function (ContainerInterface $c) {
        return new DisplayAllUsersAction(
            $c->get(AuthentificationServiceInterface::class)
        );
    },
    DisplayUserByIdAction::class => function (ContainerInterface $c) {
        return new DisplayUserByIdAction(
            $c->get(AuthentificationServiceInterface::class)
        );
    },
];
