<?php

namespace geoquizz\core\dto\partie;

use geoquizz\core\dto\DTO;

class InputPartieDTO extends DTO
{
    protected string $email;
    protected string $nom;
    protected string $token;
    protected int $nb_photos;
    protected int $score;
    protected string $theme;

    public function __construct(string $email, string $nom, string $token, int $nb_photos, int $score, string $theme) {
        $this->email = $email;
        $this->nom = $nom;
        $this->token = $token;
        $this->nb_photos = $nb_photos;
        $this->score = $score;
        $this->theme = $theme;
    }
}