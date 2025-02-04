<?php

namespace geoquizz\core\services\stats;

use geoquizz\core\dto\stats\DisplayDetailsStatsDTO;
use geoquizz\core\dto\stats\InputStatsDTO;
use geoquizz\core\dto\stats\UpdateStatsMeilleurScoreDTO;
use geoquizz\core\dto\stats\UpdateStatsNbPartieDTO;
use geoquizz\core\dto\stats\UpdateStatsPireCoupsDTO;
use geoquizz\core\dto\stats\UpdateStatsScoreMoyenDTO;
use geoquizz\core\dto\stats\UpdateStatsScoreTotalDTO;

interface StatsServiceInterface
{
    public function createStats(InputStatsDTO $inputStatsDTO) : DisplayDetailsStatsDTO;
    public function getStats(string $id) : DisplayDetailsStatsDTO;
    public function getStatsByPlayer(string $id) : DisplayDetailsStatsDTO;
    public function deleteStats(string $id) : void;
    public function searchStats(string $search) : array;
    public function getAllStats() : array;
    public function updateStatsMeilleurScore(UpdateStatsMeilleurScoreDTO $meilleurScoreDTO) : DisplayDetailsStatsDTO;
    public function updateStatsNbPartie(UpdateStatsNbPartieDTO $updateStatsNbPartieDTO) : DisplayDetailsStatsDTO;
    public function updateStatsPireCoups(UpdateStatsPireCoupsDTO $updateStatsPireCoupsDTO) : DisplayDetailsStatsDTO;
    public function updateStatsScoreMoyen(UpdateStatsScoreMoyenDTO $updateStatsScoreMoyenDTO) : DisplayDetailsStatsDTO;
    public function updateStatsScoreTotal(UpdateStatsScoreTotalDTO $updateStatsScoreTotalDTO) : DisplayDetailsStatsDTO;
}
