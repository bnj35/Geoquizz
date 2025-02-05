<?php

namespace geoquizz\core\repositoryInterfaces;

use geoquizz\core\domain\entities\stats\Stats;

interface StatsRepositoryInterface
{
    public function save(Stats $stats): string;
    public function delete(string $id): void;
    public function find(string $id): ?Stats;
    public function findAll(): array;
    public function findByUserId(string $userId): Stats;
    public function search(string $search): array;

}
