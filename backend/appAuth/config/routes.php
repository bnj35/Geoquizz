<?php
declare(strict_types=1);


use geoquizz\application\actions\DisplayAllUsersAction;
use geoquizz\application\actions\DisplayUserByIdAction;
use geoquizz\application\actions\RefreshAction;
use geoquizz\application\actions\SigninAction;
use geoquizz\application\actions\SignupAction;
use geoquizz\application\actions\ValidateAction;

return function(\Slim\App $app):\Slim\App {


    $app->get('/refresh[/]', RefreshAction::class)->setName('refresh');
    $app->post('/signin[/]', SigninAction::class)->setName('signin');
    $app->post('/signup[/]', SignupAction::class)->setName('signup');
    $app->post('/validate[/]', ValidateAction::class)->setName('validate');
    $app->get('/users[/]', DisplayAllUsersAction::class)->setName('users');
    $app->get('/users/{id}[/]', DisplayUserByIdAction::class)->setName('user');

    return $app;
};
