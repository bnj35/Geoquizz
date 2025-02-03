<?php

namespace geoquizz\application\provider\auth;

use geoquizz\core\dto\auth\AuthDTO;
use geoquizz\core\dto\auth\CredentialsDTO;

interface AuthProviderInterface
{
    public function register(CredentialsDTO $credentialsDTO): void;
    public function signin(CredentialsDTO $credentialsDTO): AuthDTO;
    public function refresh(string $token): AuthDTO;
    public function getSignedInUser(string $token): AuthDTO;

}
