<?php
declare(strict_types=1);


use geoquizz\application\actions\RefreshAction;
use geoquizz\application\actions\SigninAction;
use geoquizz\application\actions\SignupAction;
use geoquizz\application\actions\ValidateAction;

return function(\Slim\App $app):\Slim\App {


    $app->get('/refresh[/]', RefreshAction::class)->setName('refresh');
    $app->post('/signin[/]', SigninAction::class)->setName('signin');
    $app->post('/signup[/]', SignupAction::class)->setName('signup');
    $app->post('/validate[/]', ValidateAction::class)->setName('validate');

    return $app;
};
