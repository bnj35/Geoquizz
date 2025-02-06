<?php

namespace geoquizz\application\actions;

use geoquizz\application\renderer\JsonRenderer;
use geoquizz\core\dto\stats\InputStatsDTO;
use geoquizz\core\services\stats\StatsServiceBadDataException;
use geoquizz\core\services\stats\StatsServiceInterface;
use geoquizz\core\services\stats\StatsServiceInternalServerErrorException;
use geoquizz\core\services\stats\StatsServiceNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;

class CreateStatAction extends AbstractAction
{
    private StatsServiceInterface $serviceStatsInterface;

    public function __construct(StatsServiceInterface $serviceStatsInterface)
    {
        $this->serviceStatsInterface = $serviceStatsInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $routeContext = RouteContext::fromRequest($rq);
            $routeParser = $routeContext->getRouteParser();

            $data = $rq->getParsedBody();
//            $placeInputValidator = Validator::key('user_id', Validator::stringType()->notEmpty())
//                ->key('score_total', Validator::intType())
//                ->key('score_moyen', Validator::intType())
//                ->key('nb_parties', Validator::intType())
//                ->key('meilleur_score', Validator::intType())
//                ->key('pire_coups', Validator::intType());
//            try {
//                $placeInputValidator->assert($data);
//            } catch (NestedValidationException $e) {
//                throw new HttpBadRequestException($rq, $e->getFullMessage());
//            }

//            if (!filter_var($data['score_total'], FILTER_VALIDATE_INT)) {
//                throw new HttpBadRequestException($rq, 'score_total must be an integer');
//            }
//            if (!filter_var($data['score_moyen'], FILTER_VALIDATE_INT)) {
//                throw new HttpBadRequestException($rq, 'score_moyen must be an integer');
//            }
//            if (!filter_var($data['nb_parties'], FILTER_VALIDATE_INT)) {
//                throw new HttpBadRequestException($rq, 'nb_parties must be an integer');
//            }
//            if (!filter_var($data['meilleur_score'], FILTER_VALIDATE_INT)) {
//                throw new HttpBadRequestException($rq, 'meilleur_score must be an integer');
//            }
//            if (!filter_var($data['pire_coups'], FILTER_VALIDATE_INT)) {
//                throw new HttpBadRequestException($rq, 'pire_coups must be an integer');
//            }

            $input_dto = new InputStatsDTO($data['user_id'], $data['score_total'], $data['score_moyen'], $data['nb_parties'], $data['meilleur_score'], $data['pire_coups']);
            $stats = $this->serviceStatsInterface->createStats($input_dto);

            $response = [
                'type' => 'collection',
                'locale' => 'fr-FR',
                'stats' => $stats
            ];

            return JsonRenderer::render($rs, 201, $response);

        } catch (StatsServiceNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        } catch (StatsServiceInternalServerErrorException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        } catch (StatsServiceBadDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }
    }
}
