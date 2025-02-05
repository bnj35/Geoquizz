<?php

namespace geoquizz\core\dto\stats;

use geoquizz\core\dto\DTO;

class UpdateStatsMeilleurScoreDTO extends DTO
{
    protected string $id;
    protected int $meilleur_score;

    public function __construct(string $id, int $meilleur_score)
    {
        $this->id = $id;
        $this->meilleur_score = $meilleur_score;
    }

}
