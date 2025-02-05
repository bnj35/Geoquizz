<?php

namespace geoquizz\core\dto\stats;

use geoquizz\core\dto\DTO;

class DeleteStatsDTO extends DTO
{
    protected string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

}
