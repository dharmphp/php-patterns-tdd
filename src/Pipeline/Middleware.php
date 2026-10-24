<?php

declare(strict_types=1);

namespace App\Pipeline;

final class Middleware
{
    /** @var list<callable(mixed, callable): mixed> */
    private array $layers = [];

    public function add(callable $layer): self
    {
        $this->layers[] = $layer;

        return $this;
    }

    public function handle(mixed $request, callable $core): mixed
    {
        $next = $core;
        foreach (array_reverse($this->layers) as $layer) {
            $next = fn ($req) => $layer($req, $next);
        }

        return $next($request);
    }
}
