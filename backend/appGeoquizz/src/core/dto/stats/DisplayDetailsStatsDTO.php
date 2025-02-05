<?php

namespace geoquizz\core\dto\stats;

use geoquizz\core\domain\entities\stats\Stats;
use geoquizz\core\dto\DTO;

class DisplayDetailsStatsDTO extends DTO
{
    protected string $id;
    protected string $user_id;
    protected int $score_total;
    protected int $score_moyen;
    protected int $nb_parties;
    protected int $meilleur_score;
    protected int $pire_coups;

    public function __construct(Stats $stats)
    {
        $this->id = $stats->getId();
        $this->user_id = $stats->getUserID();
        $this->score_total = $stats->getScoreTotal();
        $this->score_moyen = $stats->getScoreMoyen();
        $this->nb_parties = $stats->getNbParties();
        $this->meilleur_score = $stats->getMeilleurScore();
        $this->pire_coups = $stats->getPireCoups();
    }

}
