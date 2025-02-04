<?php

namespace geoquizz\application\actions;

use geoquizz\application\renderer\JsonRenderer;
use geoquizz\core\services\stats\StatsServiceInterface;
use geoquizz\core\services\stats\StatsServiceInternalServerErrorException;
use geoquizz\core\services\stats\StatsServiceNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;

class DisplayStatsAction extends AbstractAction
{
    private StatsServiceInterface $serviceStatsInterface;

    public function __construct(StatsServiceInterface $serviceStatsInterface)
    {
        $this->serviceStatsInterface = $serviceStatsInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $stats = $this->serviceStatsInterface->getAllStats();

            $response = [
                'type' => 'collection',
                'locale' => 'fr-FR',
                'stats' => $stats
            ];

            JsonRenderer::render($rs, 200, $response);

        } catch (StatsServiceNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());

        } catch (StatsServiceInternalServerErrorException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}
