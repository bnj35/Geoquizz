<?php

namespace geoquizz\core\services\stats;


use geoquizz\core\domain\entities\stats\Stats;
use geoquizz\core\dto\stats\DisplayDetailsStatsDTO;
use geoquizz\core\dto\stats\DisplayStatsDTO;
use geoquizz\core\dto\stats\InputStatsDTO;
use geoquizz\core\dto\stats\UpdateStatsMeilleurScoreDTO;
use geoquizz\core\dto\stats\UpdateStatsNbPartieDTO;
use geoquizz\core\dto\stats\UpdateStatsPireCoupsDTO;
use geoquizz\core\dto\stats\UpdateStatsScoreMoyenDTO;
use geoquizz\core\dto\stats\UpdateStatsScoreTotalDTO;
use geoquizz\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use geoquizz\core\repositoryInterfaces\RepositoryInternalServerError;
use geoquizz\core\repositoryInterfaces\StatsRepositoryInterface;

class StatsService implements StatsServiceInterface
{
    protected $statsRepositoryInterface;

    public function __construct(StatsRepositoryInterface $statsRepositoryInterface)
    {
        $this->statsRepositoryInterface = $statsRepositoryInterface;
    }

    public function createStats(InputStatsDTO $inputStatsDTO): DisplayDetailsStatsDTO
    {
        try{
            $stats = new Stats($inputStatsDTO->id, $inputStatsDTO->score_total, $inputStatsDTO->score_moyen, $inputStatsDTO->nb_partie, $inputStatsDTO->meilleur_score, $inputStatsDTO->pire_coups);
            $id = $this->statsRepositoryInterface->save($stats);
            $stats->setID($id);
            return new DisplayDetailsStatsDTO($stats);
        }catch (RepositoryEntityNotFoundException $e){
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }catch (RepositoryInternalServerError $e){
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }

    public function getStats(string $id): DisplayDetailsStatsDTO
    {
        try{
            $stats = $this->statsRepositoryInterface->find($id);
            return new DisplayDetailsStatsDTO($stats);
        }catch (RepositoryEntityNotFoundException $e){
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }catch (RepositoryInternalServerError $e){
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }

    public function getStatsByPlayer(string $id): DisplayDetailsStatsDTO
    {
        try{
            $stats = $this->statsRepositoryInterface->findByUserId($id);
            return new DisplayDetailsStatsDTO($stats);
        }catch (RepositoryEntityNotFoundException $e){
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }catch (RepositoryInternalServerError $e){
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }

    public function deleteStats(string $id): void
    {
        try{
            $this->statsRepositoryInterface->delete($id);
        }catch (RepositoryEntityNotFoundException $e){
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }catch (RepositoryInternalServerError $e){
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }

    public function searchStats(string $search): array
    {
        try{
            $stats = $this->statsRepositoryInterface->search($search);
            $statsDTO = [];
            foreach ($stats as $stat){
                $statsDTO[] = new DisplayStatsDTO($stat);
            }
            return $statsDTO;
        }catch (RepositoryEntityNotFoundException $e){
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }catch (RepositoryInternalServerError $e){
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }

    public function getAllStats(): array
    {
        try{
            $stats = $this->statsRepositoryInterface->findAll();
            $statsDTO = [];
            foreach ($stats as $stat){
                $statsDTO[] = new DisplayStatsDTO($stat);
            }
            return $statsDTO;
        }catch (RepositoryEntityNotFoundException $e){
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }catch (RepositoryInternalServerError $e){
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }

    public function updateStatsMeilleurScore(UpdateStatsMeilleurScoreDTO $meilleurScoreDTO): DisplayDetailsStatsDTO
    {
        try{
            $stats = $this->statsRepositoryInterface->find($meilleurScoreDTO->id);
            $stats->setMeilleurScore($meilleurScoreDTO->meilleur_score);
            $this->statsRepositoryInterface->save($stats);
            return new DisplayDetailsStatsDTO($stats);
        }catch (RepositoryEntityNotFoundException $e){
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }catch (RepositoryInternalServerError $e){
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }

    public function updateStatsNbPartie(UpdateStatsNbPartieDTO $updateStatsNbPartieDTO): DisplayDetailsStatsDTO
    {
        try{
            $stats = $this->statsRepositoryInterface->find($updateStatsNbPartieDTO->id);
            $stats->setNbParties($updateStatsNbPartieDTO->nb_partie);
            $this->statsRepositoryInterface->save($stats);
            return new DisplayDetailsStatsDTO($stats);
        }catch (RepositoryEntityNotFoundException $e){
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }catch (RepositoryInternalServerError $e){
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }

    public function updateStatsPireCoups(UpdateStatsPireCoupsDTO $updateStatsPireCoupsDTO): DisplayDetailsStatsDTO
    {
        try{
            $stats = $this->statsRepositoryInterface->find($updateStatsPireCoupsDTO->id);
            $stats->setPireCoups($updateStatsPireCoupsDTO->pire_coups);
            $this->statsRepositoryInterface->save($stats);
            return new DisplayDetailsStatsDTO($stats);
        }catch (RepositoryEntityNotFoundException $e){
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }catch (RepositoryInternalServerError $e){
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }

    public function updateStatsScoreMoyen(UpdateStatsScoreMoyenDTO $updateStatsScoreMoyenDTO): DisplayDetailsStatsDTO
    {
        try{
            $stats = $this->statsRepositoryInterface->find($updateStatsScoreMoyenDTO->id);
            $stats->setScoreMoyen($updateStatsScoreMoyenDTO->score_moyen);
            $this->statsRepositoryInterface->save($stats);
            return new DisplayDetailsStatsDTO($stats);
        }catch (RepositoryEntityNotFoundException $e){
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }catch (RepositoryInternalServerError $e){
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }

    public function updateStatsScoreTotal(UpdateStatsScoreTotalDTO $updateStatsScoreTotalDTO): DisplayDetailsStatsDTO
    {
        try{
            $stats = $this->statsRepositoryInterface->find($updateStatsScoreTotalDTO->id);
            $stats->setScoreTotal($updateStatsScoreTotalDTO->score_total);
            $this->statsRepositoryInterface->save($stats);
            return new DisplayDetailsStatsDTO($stats);
        }catch (RepositoryEntityNotFoundException $e){
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }catch (RepositoryInternalServerError $e) {
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }
}
