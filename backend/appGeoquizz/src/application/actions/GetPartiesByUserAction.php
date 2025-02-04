<?php
namespace geoquizz\app\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
//exceptions
use Slim\Exception\HttpInternalServerErrorException;
//routing
use Slim\Routing\RouteContext;
//renderer
use geoquizz\src\renderer\JsonRenderer;
//services
use geoquizz\core\services\partie\ServicePartieInterface;

class GetPartiesByUserAction extends AbstractAction {

    private ServicePartieInterface $partieService;

    public function __construct(ServicePartieInterface $partieService) {
        $this->partieService = $partieService;
    }   

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        try {
            $userId = $args['id'];
            $parties = $this->partieService->getPartiesByUser($userId);
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();
            $urlParties = $routeParser->urlFor('getPartiesByUser', ['id' => $userId]);

            $parties = array_map(function($partie) use ($routeParser) {
                $urlPartie = $routeParser->urlFor('getPartieById', ['id' => $partie->getId()]);
                return [
                    "id" => $partie->getId(),
                    "nom" => $partie->getNom(),
                    "token" => $partie->getToken(),
                    "nb_photos" => $partie->getNbPhotos(),
                    "score" => $partie->getScore(),
                    "theme" => $partie->getTheme(),
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

        } catch (\Exception $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}