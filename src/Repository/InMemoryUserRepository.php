<?php

declare(strict_types=1);

namespace App\Repository;

final class InMemoryUserRepository implements UserRepository
{
    /** @var array<int, User> */
    private array $rows = [];
    private int $nextId = 1;

    public function save(User $user): User
    {
        $existing = $this->findByEmail($user->email);
        if ($existing !== null && $existing->id !== $user->id) {
            throw new DuplicateEmail($user->email);
        }
        $saved = $user->id === null ? $user->withId($this->nextId++) : $user;
        $this->rows[$saved->id] = $saved;

        return $saved;
    }

    public function find(int $id): ?User
    {
        return $this->rows[$id] ?? null;
    }

    public function findByEmail(string $email): ?User
    {
        foreach ($this->rows as $u) {
            if (strcasecmp($u->email, $email) === 0) {
                return $u;
            }
        }

        return null;
    }

    public function active(): array
    {
        return array_values(array_filter($this->rows, fn (User $u) => $u->active));
    }

    public function delete(int $id): void
    {
        unset($this->rows[$id]);
    }
}
