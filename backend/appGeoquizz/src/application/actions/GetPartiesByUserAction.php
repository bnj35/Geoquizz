<?php
namespace geoquizz\application\actions;

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
use geoquizz\application\actions\AbstractAction;

class GetPartiesByUserAction extends AbstractAction {

    private ServicePartieInterface $partieService;

    public function __construct(ServicePartieInterface $partieService) {
        $this->partieService = $partieService;
    }   

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        try {
            $userId = $args['id'];
            $parties = $this->partieService->getPartieByUserId($userId);
            $response = [
                'type' => 'collection',
                'local' => 'FR-fr',
                'parties' => $parties,
                'links' => [
                    'self' => [
                        'href' => '/users/' . $userId . '/parties',
                    ]
                ]
            ];

            return JsonRenderer::render($rs, 200, $response);

        } catch (\Exception $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}