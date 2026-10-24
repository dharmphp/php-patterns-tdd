<?php

declare(strict_types=1);

namespace App\Pipeline;

final class Pipeline
{
    /** @var list<callable> */
    private array $stages = [];

    public static function make(): self
    {
        return new self();
    }

    public function pipe(callable $stage): self
    {
        $clone = clone $this;
        $clone->stages[] = $stage;

        return $clone;
    }

    public function process(mixed $payload): mixed
    {
        return array_reduce($this->stages, fn ($carry, callable $stage) => $stage($carry), $payload);
    }

    public function __invoke(mixed $payload): mixed
    {
        return $this->process($payload);
    }
}
