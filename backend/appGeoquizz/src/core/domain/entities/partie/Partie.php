<?php

namespace geoquizz\core\domain\entities\partie;

//entities
use geoquizz\core\domain\entities\Entity;
//dto
use geoquizz\core\dto\partie\PartieDTO;

class Partie extends Entity
{
    protected string $nom;
    protected string $token;
    protected int $nb_photos;
    protected int $score;
    protected string $theme;
    protected int $status = 0;
    protected int $temps;
    protected int $distance = 10000;



    public function __construct(string $nom, string $token, int $nb_photos, int $score, string $theme, string $temps)
    {
        $this->nom = $nom;
        $this->token = $token;
        $this->nb_photos = $nb_photos;
        $this->score = $score;
        $this->theme = $theme;
        $this->temps = $temps;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getNbPhotos(): int
    {
        return $this->nb_photos;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getTheme(): string
    {
        return $this->theme;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getTemps(): int
    {
        return $this->temps;
    }

    public function getDistance(): int
    {
        return $this->distance;
    }

    public function setStatut(int $statut): void
    {
        $this->status = $statut;
    }

    public function setTemps(int $temps): void
    {
        $this->temps = $temps;
    }

    public function setDistance(int $distance): void
    {
        $this->distance = $distance;
    }

    public function toDTO(): PartieDTO
    {
        return new PartieDTO($this);
    }
}
