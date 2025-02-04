<?php

namespace geoquizz\core\dto\partie;

use geoquizz\core\domain\entities\partie\Partie;
use geoquizz\core\dto\DTO;

class PartieDTO extends DTO
{
    protected string $id;
    protected string $nom;
    protected string $token;
    protected int $nb_photos;
    protected int $score;
    protected string $theme;

    public function __construct(Partie $p)
    {
        $this->id = $p->getID();
        $this->nom = $p->getNom();
        $this->token = $p->getToken();
        $this->nb_photos = $p->getNbPhotos();
        $this->score = $p->getScore();
        $this->theme = $p->getTheme();
    }
}