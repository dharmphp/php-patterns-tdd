<?php

declare(strict_types=1);

namespace App\Repository;

final class User
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $email,
        public readonly string $name,
        public readonly bool $active = true,
    ) {
    }

    public function withId(int $id): self
    {
        return new self($id, $this->email, $this->name, $this->active);
    }

    public function deactivated(): self
    {
        return new self($this->id, $this->email, $this->name, false);
    }
}
