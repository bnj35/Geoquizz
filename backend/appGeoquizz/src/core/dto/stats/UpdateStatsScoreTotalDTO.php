<?php

namespace geoquizz\core\dto\stats;

use geoquizz\core\dto\DTO;

class UpdateStatsScoreTotalDTO extends DTO
{
    protected string $id;
    protected int $score_total;

    public function __construct(string $id, int $score_total)
    {
        $this->id = $id;
        $this->score_total = $score_total;
    }

}
