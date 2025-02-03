<?php

namespace geoquizz\core\dto\auth;

use geoquizz\core\dto\DTO;

class CredentialsDTO extends DTO
{
    protected string $email;
    protected string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

}
