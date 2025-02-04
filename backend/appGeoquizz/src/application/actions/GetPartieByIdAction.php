<?php

namespace geoquizz\application\actions;

use geoquizz\core\services\partie\ServicePartieInternalServerError;
use geoquizz\core\services\partie\ServicePartieInvalidDataException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

//exceptions
use Slim\Exception\HttpInternalServerErrorException;

//routing
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;

//renderer
use geoquizz\application\renderer\JsonRenderer;

//services
use geoquizz\core\services\partie\ServicePartieInterface;
use geoquizz\application\actions\AbstractAction;

class GetPartieByIdAction extends AbstractAction
{

    private ServicePartieInterface $partieService;

    public function __construct(ServicePartieInterface $partieService)
    {
        $this->partieService = $partieService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $partieId = $args['id'];
            $partie = $this->partieService->getPartieById($partieId);
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $urlPartie = $routeParser->urlFor('getPartieById', ['id' => $partie->id]);
            $result = [
                "type" => "resource",
                "locale" => "fr-FR",
                "partie" => $partie,
                "links" => [
                    "self" => ["href" => $urlPartie],
                ]
            ];
            return JsonRenderer::render($rs, 200, $result);
        } catch (ServicePartieInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        } catch (ServicePartieInvalidDataException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }
    }
}
