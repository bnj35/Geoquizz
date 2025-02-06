<?php
namespace geoquizz\infrastructure\db;

use Ramsey\Uuid\Uuid;
use geoquizz\core\domain\entities\partie\Partie;
use geoquizz\core\repositoryInterfaces\PartieRepositoryInterface;
use geoquizz\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use geoquizz\core\repositoryInterfaces\RepositoryInternalServerError;

class PDOPartieRepository implements PartieRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Partie $p): string
    {
        try {
            if ($p->getID() !== null) {
                $stmt = $this->pdo->prepare("UPDATE parties SET nom = :nom, token = :token, nb_photos = :nb_photos, score = :score, theme = :theme, status = :status, temps = :temps, distance = :distance WHERE id = :id");
            } else {
                $id = Uuid::uuid4()->toString();
                $p->setID($id);
                $stmt = $this->pdo->prepare("INSERT INTO parties (id, nom, token, nb_photos, score, theme, status, temps, distance) VALUES (:id, :nom, :token, :nb_photos, :score, :theme, :status, :temps, :distance)");
            }
            $stmt->execute([
                'id' => $p->getID(),
                'nom' => $p->getNom(),
                'token' => $p->getToken(),
                'nb_photos' => $p->getNbPhotos(),
                'score' => $p->getScore(),
                'theme' => $p->getTheme(),
                'status' => $p->getStatus(),
                'temps' => $p->getTemps(),
                'distance' => $p->getDistance()
            ]);
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError($e->getMessage());
        }
        return $p->getID();
    }

    public function getPartieById(string $id): Partie
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM parties WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $partie = $stmt->fetch();
            if ($partie === false) {
                throw new RepositoryEntityNotFoundException("Partie not found");
            }
            $p = new Partie($partie['nom'], $partie['token'], (int)$partie['nb_photos'], (int)$partie['score'], $partie['theme'],$partie['temps']);
            $p->setID($partie['id']);
            $p->setStatut($partie['status']);
            $p->setDistance($partie['distance']);
            return $p;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while fetching partie");
        }
    }

    public function getAllParties(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM parties");
            $parties = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if (empty($parties)) {
                error_log("No parties found in database");
            }
            $result = [];
            foreach ($parties as $partie) {
                $p = new Partie($partie['nom'], $partie['token'], (int)$partie['nb_photos'], (int)$partie['score'], $partie['theme'], $partie['temps']);
                $p->setID($partie['id']);
                $p->setStatut($partie['status']);
                $p->setDistance($partie['distance']);
                $result[] = $p;
            }
            return $result;
        } catch (\PDOException $e) {
            error_log("PDOException: " . $e->getMessage());
            throw new RepositoryInternalServerError("Error while fetching parties");
        }
    }

    public function deleteById(string $id): void
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM parties WHERE id = :id");
            $stmt->execute(['id' => $id]);
            if ($stmt->rowCount() === 0) {
                throw new RepositoryEntityNotFoundException("Partie not found");
            }
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while deleting partie");
        }
    }

    public function getPartieByUserId(string $user_id): array
    {
        try {
            $stmt = $this->pdo->prepare("
            SELECT p.* 
            FROM parties p
            JOIN partie_users pu ON p.id = pu.partie_id
            WHERE pu.user_id = :user_id
        ");
            $stmt->execute(['user_id' => $user_id]);
            $parties = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if (empty($parties)) {
                throw new RepositoryEntityNotFoundException("No parties found for this user");
            }
            $result = [];
            foreach ($parties as $partie) {
                $p = new Partie($partie['nom'], $partie['token'], (int)$partie['nb_photos'], (int)$partie['score'], $partie['theme'], $partie['temps']);
                $p->setID($partie['id']);
                $p->setStatut($partie['status']);
                $p->setDistance($partie['distance']);
                $result[] = $p;
            }
            return $result;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while fetching parties");
        }
    }

    public function updateScore(string $id, int $score): void
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE parties SET score = score + :score WHERE id = :id");
            $stmt->execute(['id' => $id, 'score' => $score]);
            if ($stmt->rowCount() === 0) {
                throw new RepositoryEntityNotFoundException("Partie not found");
            }
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError("Error while updating partie");
        }
    }

    public function closePartie(string $id):void
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE parties SET status = 1 WHERE id = :id");
            $stmt->execute(['id' => $id]);
            if ($stmt->rowCount() === 0) {
                throw new RepositoryEntityNotFoundException("Partie not found");
            }
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }

    public function getUserIdByPartieId(string $id): string 
    {
        try{
            $stmt = $this->pdo->prepare("SELECT user_id FROM partie_users WHERE partie_id = :id");
            $stmt->execute(['id' => $id]);
            $userId = $stmt->fetchColumn();
            if ($stmt->rowCount() === 0) {
                throw new RepositoryEntityNotFoundException("user id not found");
            }
            return $userId;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }

    public function setPartieImage(array $images, string $partie_id):array
    {
        try{
            $values = [];
            for($i=0; $i<count($images); $i++){
                $values[] = "('$partie_id', '{$images[$i]['id']}')";
            }
            $values = implode(", ", $values);
            $stmt = $this->pdo->prepare("INSERT INTO partie_images (partie_id, image_id) VALUES $values");
            $stmt->execute();
            return $images;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError($e->getMessage());
        }
    } 

    public function setUserId(string $id, string $user_id):void
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO partie_users (partie_id, user_id) VALUES (:id, :user_id)");
            $stmt->execute(['id' => $id, 'user_id' => $user_id]);
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }
}
