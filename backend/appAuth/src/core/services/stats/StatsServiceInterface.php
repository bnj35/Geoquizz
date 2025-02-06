<?php

namespace geoquizz\core\services\stats;

use geoquizz\core\domain\entities\stats\Stats;
use geoquizz\core\dto\stats\InputStatsDTO;

interface StatsServiceInterface
{
    public function createStats(InputStatsDTO $inputStatsDTO): Stats;

}
