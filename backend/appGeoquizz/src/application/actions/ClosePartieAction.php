<?php

namespace geoquizz\application\actions;

use geoquizz\application\actions\AbstractAction;
use geoquizz\core\services\partie\ServicePartieInterface;

use Psr\Http\Message\ResponseInterface;

use geoquizz\core\services\partie\ServicePartieInternalServerError;
use geoquizz\core\services\partie\ServicePartieInvalidDataException;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;

//renderer
use geoquizz\application\renderer\JsonRenderer;

class ClosePartieAction extends AbstractAction
{
    private ServicePartieInterface $partieService;

    public function __construct(ServicePartieInterface $partieService)
    {
        $this->partieService = $partieService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {

        try {
            $id = $args['id'];
            $data = $rq->getParsedBody();
            $this->partieService->closePartie($id);
            $partie = $this->partieService->getPartieById($id);
            $response = [
                'type' => 'resource',
                'local' => 'FR-fr',
                'partie' => $partie,
                'links' => [
                    'self' => [
                        'href' => '/parties/' . $id,
                    ]
                ]
            ];

            return JsonRenderer::render($rs, 200, $response);
        } catch (ServicePartieInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        } catch (ServicePartieInvalidDataException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }

    }


}
