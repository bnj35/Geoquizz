<?php

namespace geoquizz\infrastructure\http;

use geoquizz\core\dto\stats\DisplayDetailsStatsDTO;
use geoquizz\core\dto\stats\InputStatsDTO;
use geoquizz\core\services\auth\AuthentificationServiceBadDataException;
use geoquizz\core\services\auth\AuthentificationServiceInternalServerErrorException;
use geoquizz\core\services\stats\StatsServiceInterface;
use GuzzleHttp\Client;
use geoquizz\core\domain\entities\stats\Stats;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;

class StatsServiceHTTP implements StatsServiceInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function createStats(InputStatsDTO $inputStatsDTO): Stats
    {
        try{
            $response = $this->client->post("/stats", [
                "user_id" => $inputStatsDTO->user_id,
                "score_total" => $inputStatsDTO->score_total,
                "score_moyen" => $inputStatsDTO->score_moyen,
                "nb_parties" => $inputStatsDTO->nb_parties,
                "meilleur_score" => $inputStatsDTO->meilleur_score,
                "pire_coups" => $inputStatsDTO->pire_coups
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return new Stats($data['user_id'], $data['score_total'], $data['score_moyen'], $data['nb_parties'], $data['meilleur_score'], $data['pire_coups']);
        }catch (ConnectException|ServerException $e) {
            throw new AuthentificationServiceInternalServerErrorException($e->getMessage());
        } catch (ClientException $e) {
            match ($e->getCode()) {
                400 => throw new AuthentificationServiceBadDataException($e->getMessage()),
            };
        }
    }

}
