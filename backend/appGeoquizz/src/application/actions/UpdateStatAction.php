<?php

namespace geoquizz\application\actions;

use geoquizz\application\renderer\JsonRenderer;
use geoquizz\core\dto\stats\UpdateStatsMeilleurScoreDTO;
use geoquizz\core\dto\stats\UpdateStatsNbPartieDTO;
use geoquizz\core\dto\stats\UpdateStatsPireCoupsDTO;
use geoquizz\core\dto\stats\UpdateStatsScoreMoyenDTO;
use geoquizz\core\dto\stats\UpdateStatsScoreTotalDTO;
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

class UpdateStatAction extends AbstractAction
{
    private StatsServiceInterface $serviceStatsInterface;

    public function __construct(StatsServiceInterface $serviceStatsInterface)
    {
        $this->serviceStatsInterface = $serviceStatsInterface;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try{
            $data = $rq->getParsedBody();
            $placeInputValidator = Validator::key('user_id', Validator::stringType())
                ->key('score_total', Validator::intType())
                ->key('score_moyen', Validator::intType())
                ->key('nb_parties', Validator::intType())
                ->key('meilleur_score', Validator::intType())
                ->key('pire_coups', Validator::intType());
            try{
                $placeInputValidator->assert($data);
            } catch (NestedValidationException $e) {
                throw new HttpBadRequestException($rq, $e->getFullMessage());
            }

            if(isset($data['score_total'])){
                if (!filter_var($data['score_total'], FILTER_VALIDATE_INT)) {
                    throw new HttpBadRequestException($rq, 'score_total must be an integer');
                }
                $dto = new UpdateStatsScoreTotalDTO($args['id'], $data['score_total']);
                $this->serviceStatsInterface->updateStatsScoreTotal($dto);
            }

            if(isset($data['score_moyen'])){
                if (!filter_var($data['score_moyen'], FILTER_VALIDATE_INT)) {
                    throw new HttpBadRequestException($rq, 'score_moyen must be an integer');
                }
                $dto = new UpdateStatsScoreMoyenDTO($args['id'], $data['score_moyen']);
                $this->serviceStatsInterface->updateStatsScoreMoyen($dto);
            }

            if(isset($data['nb_parties'])){
                if (!filter_var($data['nb_parties'], FILTER_VALIDATE_INT)) {
                    throw new HttpBadRequestException($rq, 'nb_parties must be an integer');
                }
                $dto = new UpdateStatsNbPartieDTO($args['id'], $data['nb_parties']);
                $this->serviceStatsInterface->updateStatsNbPartie($dto);
            }

            if(isset($data['meilleur_score'])){
                if (!filter_var($data['meilleur_score'], FILTER_VALIDATE_INT)) {
                    throw new HttpBadRequestException($rq, 'meilleur_score must be an integer');
                }
                $dto = new UpdateStatsMeilleurScoreDTO($args['id'], $data['meilleur_score']);
                $this->serviceStatsInterface->updateStatsMeilleurScore($dto);
            }

            if(isset($data['pire_coups'])){
                if (!filter_var($data['pire_coups'], FILTER_VALIDATE_INT)) {
                    throw new HttpBadRequestException($rq, 'pire_coups must be an integer');
                }
                $dto = new UpdateStatsPireCoupsDTO($args['id'], $data['pire_coups']);
                $this->serviceStatsInterface->updateStatsPireCoups($dto);
            }
            $stats = $this->serviceStatsInterface->getStats($args['id']);
            $response = [
                'type' => 'collection',
                'locale' => 'fr-FR',
                'stats' => $stats
            ];
            return JsonRenderer::render($rs, 200, $response);
        }catch (StatsServiceNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        } catch (StatsServiceInternalServerErrorException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }catch (StatsServiceBadDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }
    }
}
