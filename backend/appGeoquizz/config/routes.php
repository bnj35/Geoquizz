<?php
declare(strict_types=1);


use geoquizz\application\actions\CreateStatAction;
use geoquizz\application\actions\DisplayStatAction;
use geoquizz\application\actions\DisplayStatsAction;
use geoquizz\application\actions\UpdateStatAction;
use geoquizz\application\actions\CreatePartieAction;
use geoquizz\application\actions\GetPartiesAction;
use geoquizz\application\actions\GetPartieByIdAction;
use geoquizz\application\actions\GetPartiesByUserAction;
use geoquizz\application\actions\UpdateScoreAction;

return function(\Slim\App $app):\Slim\App {

    $app->post('/parties', CreatePartieAction::class)->setName('createPartie');

    $app->get('/parties', GetPartiesAction::class)->setName('getParties');

    $app->get('/stats[/]', DisplayStatsAction::class);
    $app->get('/stats/{id}[/]', DisplayStatAction::class);
    $app->post('/stats[/]', CreateStatAction::class);
    $app->put('/stats/{id}[/]', UpdateStatAction::class);

    $app->get('/parties/{id}', GetPartieByIdAction::class)->setName('getPartieById');

    $app->get('/users/{id}/parties', GetPartiesByUserAction::class)->setName('getPartiesByUser');

    $app->patch('/parties/{id}/score', UpdateScoreAction::class)->setName('updateScore');

    return $app;
};
