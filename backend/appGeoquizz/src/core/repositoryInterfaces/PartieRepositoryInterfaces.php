<?php

namespace geoquizz\core\repositoryInterfaces;

use geoquizz\core\domain\entities\parties\Partie;

interface PartieRepositoryInterface
{

    public function createPartie(Partie $p): Partie;

    public function getParties(): array;

    public function getPartieById(string $id): Partie;

    public function getPartiesByUser(string $id): array;

}