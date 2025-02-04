<?php
declare(strict_types=1);


use geoquizz\application\actions\CreateStatAction;
use geoquizz\application\actions\DisplayStatAction;
use geoquizz\application\actions\DisplayStatsAction;
use geoquizz\application\actions\UpdateStatAction;

return function(\Slim\App $app):\Slim\App {


    $app->get('/stats[/]', DisplayStatsAction::class);
    $app->get('/stats/{id}[/]', DisplayStatAction::class);
    $app->post('/stats[/]', CreateStatAction::class);
    $app->put('/stats/{id}[/]', UpdateStatAction::class);


    return $app;
};
