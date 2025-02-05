<?php

namespace geoquizz\core\services\partie;

//dto
use geoquizz\core\dto\partie\InputPartieDTO;
use geoquizz\core\dto\partie\PartieDTO;

interface ServicePartieInterface
{
    public function createPartie(InputPartieDTO $dto): PartieDTO;
    public function getAllParties(): array;
    public function getPartieById(string $id): PartieDTO;
    public function getPartieByUserId(string $userId): array;
    public function updateScore(string $id, int $score): void;
    public function closePartie(string $id):void;
}