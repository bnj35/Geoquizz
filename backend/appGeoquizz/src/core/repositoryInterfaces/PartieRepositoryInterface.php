<?php

namespace geoquizz\core\repositoryInterfaces;

use geoquizz\core\domain\entities\partie\Partie;

interface PartieRepositoryInterface
{
    public function save(Partie $p): string;

    public function getAllParties(): array;

    public function getPartieById(string $id): Partie;

    public function getPartiesByEmail(string $email): array;

    public function getPartieByUserId(string $user_id): array;

    public function updateScore(string $id, int $score): void;

    public function closePartie(string $id):void;
}
