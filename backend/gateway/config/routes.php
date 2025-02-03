<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use GuzzleHttp\Client;

return function(App $app):App {

    $app->get('/', \geoquizz\application\actions\HomeAction::class);

    //micro-service geoquiee

    

    return $app;
};