<?php

namespace geoquizz\application\actions;

use geoquizz\core\services\partie\ServicePartieInternalServerError;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

//exceptions
use Slim\Exception\HttpInternalServerErrorException;

//routing
use Slim\Routing\RouteContext;

//renderer
use geoquizz\application\renderer\JsonRenderer;

//services
use geoquizz\core\services\partie\ServicePartieInterface;
use geoquizz\core\services\partie\ServicePartie;


class GetPartiesAction extends AbstractAction
{
    private ServicePartieInterface $partieService;

    public function __construct(ServicePartieInterface $partieService)
    {
        $this->partieService = $partieService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $parties = $this->partieService->getAllParties();
            if (empty($parties)) {
                error_log("No parties found");
            }
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $urlParties = $routeParser->urlFor('getParties');
            $parties = array_map(function ($partie) use ($routeParser) {
                $urlPartie = $routeParser->urlFor('getPartieById', ['id' => $partie->id]);
                return [
                    "id" => $partie->id,
                    "nom" => $partie->nom,
                    "token" => $partie->token,
                    "nb_photos" => $partie->nb_photos,
                    "score" => $partie->score,
                    "theme" => $partie->theme,
                    "links" => [
                        "self" => ['href' => $urlPartie]
                    ]
                ];
            }, $parties);

            $result = [
                "type" => "collection",
                "locale" => "fr-FR",
                "parties" => $parties,
                "links" => [
                    "self" => ["href" => $urlParties],
                ]
            ];
            return JsonRenderer::render($rs, 200, $result);

        } catch (ServicePartieInternalServerError $e) {
            error_log("Exception: " . $e->getMessage()); // Log the exception message
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}