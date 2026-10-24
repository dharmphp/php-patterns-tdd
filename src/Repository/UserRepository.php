<?php

declare(strict_types=1);

namespace App\Repository;

interface UserRepository
{
    public function save(User $user): User;

    public function find(int $id): ?User;

    public function findByEmail(string $email): ?User;

    /** @return list<User> */
    public function active(): array;

    public function delete(int $id): void;
}
