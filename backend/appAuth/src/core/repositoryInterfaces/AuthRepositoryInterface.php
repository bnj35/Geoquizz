<?php

namespace geoquizz\core\repositoryInterfaces;

use geoquizz\core\domain\entities\user\User;

interface AuthRepositoryInterface
{
    public function save(User $user): string;
    public function getUserByEmail(string $email): User;
    public function getUserById(string $id): User;
    public function getUsersByRole(int $role): array;
    public function getUsers(): array;

}
