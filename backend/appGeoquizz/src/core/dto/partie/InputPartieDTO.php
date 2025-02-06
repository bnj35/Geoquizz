<?php

namespace geoquizz\core\dto\partie;

use geoquizz\core\dto\DTO;

class InputPartieDTO extends DTO
{
    protected string $nom;
    protected string $token;
    protected int $nb_photos;
    protected int $score;
    protected string $theme;
    protected string $temps;
    protected string $user_id;

    public function __construct(string $nom, string $token, int $nb_photos, int $score, string $theme, string $temps, string $user_id) {
        $this->nom = $nom;
        $this->token = $token;
        $this->nb_photos = $nb_photos;
        $this->score = $score;
        $this->theme = $theme;
        $this->temps = $temps;
        $this->user_id = $user_id;
    }
}
