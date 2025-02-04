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

class UpdateScoreAction extends AbstractAction 
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

            if (!filter_var($data["score"], FILTER_VALIDATE_INT)) {
                throw new HttpBadRequestException($rq, "Bad data format score");
            }
            
            $score = $data['score'];
            $this->partieService->updateScore($id, $score);
            $response = [
                'type' => 'resource',
                'local' => 'FR-fr',
                'message' => 'Score updated successfully',
                'links' => [
                    'self' => [
                        'href' => '/parties/' . $id,
                    ]
                ]
            ];

            return JsonRenderer::render($rs, 200, $response);

        } catch (\Exception $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }
    }
}