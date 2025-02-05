<?php

namespace geoquizz\core\dto\stats;

use geoquizz\core\dto\DTO;

class UpdateStatsNbPartieDTO extends DTO
{
    protected string $id;
    protected int $nb_partie;

    public function __construct(string $id, int $nb_partie)
    {
        $this->id = $id;
        $this->nb_partie = $nb_partie;
    }

}
