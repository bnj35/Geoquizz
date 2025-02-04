<?php

namespace geoquizz\infrastructure\db;

use geoquizz\core\domain\entities\stats\Stats;
use geoquizz\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use geoquizz\core\repositoryInterfaces\RepositoryInternalServerError;
use geoquizz\core\repositoryInterfaces\StatsRepositoryInterface;
use Ramsey\Uuid\Uuid;

class PDOStatsRepository implements StatsRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Stats $stats): string
    {
        try{
            if($stats->getID() === null){
                $id = Uuid::uuid4()->toString();
                $stats->setID($id);
                $sql = "INSERT INTO stats (id, user_id, score_total, score_moyen, nb_parties, meilleur_score, pire_coups) VALUES (:id, :user_id, :score_total, :score_moyen, :nb_parties, :meilleur_score, :pire_coups)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    'id' => $stats->getID(),
                    'user_id' => $stats->getUserID(),
                    'score_total' => $stats->getScoreTotal(),
                    'score_moyen' => $stats->getScoreMoyen(),
                    'nb_parties' => $stats->getNbParties(),
                    'meilleur_score' => $stats->getMeilleurScore(),
                    'pire_coups' => $stats->getPireCoups()
                ]);
            } else {
                $sql = "UPDATE stats SET user_id = :user_id, score_total = :score_total, score_moyen = :score_moyen, nb_parties = :nb_parties, meilleur_score = :meilleur_score, pire_coups = :pire_coups WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    'id' => $stats->getID(),
                    'user_id' => $stats->getUserID(),
                    'score_total' => $stats->getScoreTotal(),
                    'score_moyen' => $stats->getScoreMoyen(),
                    'nb_parties' => $stats->getNbParties(),
                    'meilleur_score' => $stats->getMeilleurScore(),
                    'pire_coups' => $stats->getPireCoups()
                ]);
            }
            return $stats->getID();
        }catch (\PDOException $e){
            throw new RepositoryInternalServerError("Error while saving stats");
        }
    }

    public function delete(string $id): void
    {
        try{
            $sql = "DELETE FROM stats WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
        }catch (\PDOException $e){
            throw new RepositoryInternalServerError("Error while deleting stats");
        }
    }

    public function find(string $id): ?Stats
    {
        try{
            $sql = "SELECT * FROM stats WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $stats = $stmt->fetch();
            if ($stats === false) {
                throw new RepositoryEntityNotFoundException("Stats not found");
            }
            $newStats = new Stats($stats['user_id'], $stats['score_total'], $stats['score_moyen'], $stats['nb_parties'], $stats['meilleur_score'], $stats['pire_coups']);
            $newStats->setID($stats['id']);
            return $newStats;
        }catch (\PDOException $e){
            throw new RepositoryInternalServerError("Error while fetching stats");
        }
    }

    public function findAll(): array
    {
        try{
            $sql = "SELECT * FROM stats";
            $stmt = $this->pdo->query($sql);
            $stats = $stmt->fetchAll();
            $result = [];
            foreach ($stats as $stat){
                $newStats = new Stats($stat['user_id'], $stat['score_total'], $stat['score_moyen'], $stat['nb_parties'], $stat['meilleur_score'], $stat['pire_coups']);
                $newStats->setID($stat['id']);
                $result[] = $newStats;
            }
            return $result;
        }catch (\PDOException $e){
            throw new RepositoryInternalServerError("Error while fetching stats");
        }
    }

    public function findByUserId(string $userId): Stats
    {
        try{
            $sql = "SELECT * FROM stats WHERE user_id = :user_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            $stats = $stmt->fetch();
            if ($stats === false) {
                throw new RepositoryEntityNotFoundException("Stats not found");
            }
            $newStats = new Stats($stats['user_id'], $stats['score_total'], $stats['score_moyen'], $stats['nb_parties'], $stats['meilleur_score'], $stats['pire_coups']);
            $newStats->setID($stats['id']);
            return $newStats;
        }catch (\PDOException $e){
            throw new RepositoryInternalServerError("Error while fetching stats");
        }
    }

    public function search(string $search): array
    {
        try{
            $sql = "SELECT * FROM stats WHERE score_total LIKE :search OR score_moyen LIKE :search OR nb_parties LIKE :search OR meilleur_score LIKE :search OR pire_coups LIKE :search";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['search' => $search]);
            $stats = $stmt->fetchAll();
            $result = [];
            foreach ($stats as $stat){
                $newStats = new Stats($stat['user_id'], $stat['score_total'], $stat['score_moyen'], $stat['nb_parties'], $stat['meilleur_score'], $stat['pire_coups']);
                $newStats->setID($stat['id']);
                $result[] = $newStats;
            }
            return $result;
        }catch (\PDOException $e){
            throw new RepositoryInternalServerError("Error while fetching stats");
        }
    }
}
