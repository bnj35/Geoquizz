<?php
<?php

namespace geoquizz\core\dto\partie;

use geoquizz\core\domain\entities\parties\Partie;
use geoquizz\core\dto\DTO;

class PartieDTO extends DTO
{
    protected string $id;
    protected string $email;
    protected string $nom;
    protected string $token;
    protected int $nb_photos;
    protected int $score;
    protected string $theme;

    public function __construct(Partie $p)
    {
        $this->id = $p->getID();
        $this->email = $p->email;
        $this->nom = $p->nom;
        $this->token = $p->token;
        $this->nb_photos = $p->nb_photos;
        $this->score = $p->score;
        $this->theme = $p->theme;
    }
}