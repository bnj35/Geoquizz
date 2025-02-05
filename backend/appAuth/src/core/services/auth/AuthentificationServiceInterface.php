<?php

namespace geoquizz\core\services\auth;

use geoquizz\core\dto\auth\AuthDTO;
use geoquizz\core\dto\auth\CredentialsDTO;

interface AuthentificationServiceInterface
{
    public function register(CredentialsDTO $credentials, int $role): string;
    public function byCredentials(CredentialsDTO $credentials): AuthDTO;
    public function getUsers(): array;
    public function getUserById(string $id): AuthDTO;

}
