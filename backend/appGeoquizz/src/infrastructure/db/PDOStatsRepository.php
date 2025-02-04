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
                $sql = "INSERT INTO stats (id, user_id, score_tot, score_moyen, nb_partie, meilleur_coup, pire_coup) VALUES (:id, :user_id, :score_tot, :score_moyen, :nb_partie, :meilleur_coup, :pire_coup)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    'id' => $stats->getID(),
                    'user_id' => $stats->getUserID(),
                    'score_tot' => $stats->getScoreTotal(),
                    'score_moyen' => $stats->getScoreMoyen(),
                    'nb_partie' => $stats->getNbParties(),
                    'meilleur_coup' => $stats->getMeilleurScore(),
                    'pire_coup' => $stats->getPireCoups()
                ]);
            } else {
                $sql = "UPDATE stats SET user_id = :user_id, score_tot = :score_tot, score_moyen = :score_moyen, nb_partie = :nb_partie, meilleur_coup = :meilleur_coup, pire_coup = :pire_coup WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    'id' => $stats->getID(),
                    'user_id' => $stats->getUserID(),
                    'score_tot' => $stats->getScoreTotal(),
                    'score_moyen' => $stats->getScoreMoyen(),
                    'nb_partie' => $stats->getNbParties(),
                    'meilleur_coup' => $stats->getMeilleurScore(),
                    'pire_coup' => $stats->getPireCoups()
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
            $newStats = new Stats($stats['user_id'], $stats['score_tot'], $stats['score_moyen'], $stats['nb_partie'], $stats['meilleur_coup'], $stats['pire_coup']);
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
                $newStats = new Stats($stat['user_id'], $stat['score_tot'], $stat['score_moyen'], $stat['nb_partie'], $stat['meilleur_coup'], $stat['pire_coup']);
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
            $newStats = new Stats($stats['user_id'], $stats['score_tot'], $stats['score_moyen'], $stats['nb_partie'], $stats['meilleur_coup'], $stats['pire_coup']);
            $newStats->setID($stats['id']);
            return $newStats;
        }catch (\PDOException $e){
            throw new RepositoryInternalServerError("Error while fetching stats");
        }
    }

    public function search(string $search): array
    {
        try{
            $sql = "SELECT * FROM stats WHERE score_tot LIKE :search OR score_moyen LIKE :search OR nb_partie LIKE :search OR meilleur_coup LIKE :search OR pire_coup LIKE :search";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['search' => $search]);
            $stats = $stmt->fetchAll();
            $result = [];
            foreach ($stats as $stat){
                $newStats = new Stats($stat['user_id'], $stat['score_tot'], $stat['score_moyen'], $stat['nb_partie'], $stat['meilleur_coup'], $stat['pire_coup']);
                $newStats->setID($stat['id']);
                $result[] = $newStats;
            }
            return $result;
        }catch (\PDOException $e){
            throw new RepositoryInternalServerError("Error while fetching stats");
        }
    }
}
