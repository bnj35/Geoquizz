<?php

namespace geoquizz\core\dto\stats;

use geoquizz\core\dto\DTO;

class InputStatsDTO extends DTO
{
    protected string $user_id;
    protected int $score_total;
    protected int $score_moyen;
    protected int $nb_parties;
    protected int $meilleur_score;
    protected int $pire_coups;

    public function __construct(string $user_id, int $score_total, int $score_moyen, int $nb_parties, int $meilleur_score, int $pire_coups)
    {
        $this->user_id = $user_id;
        $this->score_total = $score_total;
        $this->score_moyen = $score_moyen;
        $this->nb_parties = $nb_parties;
        $this->meilleur_score = $meilleur_score;
        $this->pire_coups = $pire_coups;
    }

}
