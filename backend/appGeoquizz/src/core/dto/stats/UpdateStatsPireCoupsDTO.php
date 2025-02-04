<?php

namespace geoquizz\core\dto\stats;

use geoquizz\core\dto\DTO;

class UpdateStatsPireCoupsDTO extends DTO
{
    protected string $id;
    protected int $pire_coups;

    public function __construct(string $id, int $pire_coups)
    {
        $this->id = $id;
        $this->pire_coups = $pire_coups;
    }

}
