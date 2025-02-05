<?php

namespace geoquizz\core\dto\stats;

use geoquizz\core\domain\entities\stats\Stats;
use geoquizz\core\dto\DTO;

class DisplayStatsDTO extends DTO
{
    protected string $id;
    protected string $user_id;
    protected int $score_total;

    public function __construct(Stats $stats)
    {
        $this->id = $stats->getId();
        $this->user_id = $stats->getUserID();
        $this->score_total = $stats->getScoreTotal();
    }

}
