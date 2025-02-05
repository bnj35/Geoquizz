<?php

namespace geoquizz\core\dto\stats;

use geoquizz\core\dto\DTO;

class UpdateStatsScoreMoyenDTO extends DTO
{
    protected string $id;
    protected int $score_moyen;

    public function __construct(string $id, int $score_moyen)
    {
        $this->id = $id;
        $this->score_moyen = $score_moyen;
    }

}
