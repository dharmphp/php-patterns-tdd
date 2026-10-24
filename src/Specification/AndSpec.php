<?php

declare(strict_types=1);

namespace App\Specification;

final class AndSpec extends Composite
{
    public function __construct(private readonly Specification $a, private readonly Specification $b)
    {
    }

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $this->a->isSatisfiedBy($candidate) && $this->b->isSatisfiedBy($candidate);
    }
}
