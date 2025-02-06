<?php

namespace geoquizz\core\repositoryInterfaces;

use geoquizz\core\domain\entities\partie\Partie;

interface PartieRepositoryInterface
{
    public function save(Partie $p): string;

    public function getAllParties(): array;

    public function getPartieById(string $id): Partie;

    public function getPartieByUserId(string $user_id): array;

    public function updateScore(string $id, int $score): void;

    public function closePartie(string $id):void;

    public function setPartieImage(array $images, string $partie_id):array;

    public function setUserId(string $id, string $user_id):void;

    public function getUserIdByPartieId(string $id): string;
}
