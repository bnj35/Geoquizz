<?php

namespace geoquizz\core\domain\entities\stats;

use geoquizz\core\domain\entities\Entity;

class Stats extends Entity
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

    public function getUserID(): string
    {
        return $this->user_id;
    }

    public function getScoreTotal(): int
    {
        return $this->score_total;
    }

    public function getScoreMoyen(): int
    {
        return $this->score_moyen;
    }

    public function getNbParties(): int
    {
        return $this->nb_parties;
    }

    public function getMeilleurScore(): int
    {
        return $this->meilleur_score;
    }

    public function getPireCoups(): int
    {
        return $this->pire_coups;
    }

    public function setUserID(string $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setScoreTotal(int $score_total): void
    {
        $this->score_total = $score_total;
    }

    public function setScoreMoyen(int $score_moyen): void
    {
        $this->score_moyen = $score_moyen;
    }

    public function setNbParties(int $nb_parties): void
    {
        $this->nb_parties = $nb_parties;
    }

    public function setMeilleurScore(int $meilleur_score): void
    {
        $this->meilleur_score = $meilleur_score;
    }

    public function setPireCoups(int $pire_coups): void
    {
        $this->pire_coups = $pire_coups;
    }
}
