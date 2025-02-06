<?php

namespace geoquizz\infrastructure\db;

use geoquizz\core\repositoryInterfaces\RepositoryAuthInterface;
use geoquizz\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use geoquizz\core\repositoryInterfaces\RepositoryInternalServerError;

class AuthRepositoryPDO implements RepositoryAuthInterface 
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function getEmailByUserId(string $id): string
    {
        try {
            $stmt = $this->pdo->prepare("SELECT email FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $email = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if ($email === false) {
                throw new RepositoryEntityNotFoundException("User not found");
            }
            $email = $email[0]['email'];
            return $email;
        } catch (\PDOException $e) {
            throw new RepositoryInternalServerError($e->getMessage());
        }
    }
}
