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

//validation
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;


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
            $score = $data['score'];

            $scoreInputValidator = Validator::key('score', Validator::intType()->notEmpty());
            try{
                $scoreInputValidator->assert($data);
                if (!filter_var($data["score"], FILTER_VALIDATE_INT)) {
                    throw new HttpBadRequestException($rq, "Bad data format score");
                }
            } catch (NestedValidationException $e) {
                throw new HttpBadRequestException($rq, $e->getFullMessage());
            }

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

        } catch (ServicePartieInternalServerError $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        } catch (ServicePartieInvalidDataException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }
    }
}