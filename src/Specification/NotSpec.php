<?php

declare(strict_types=1);

namespace App\Specification;

final class NotSpec extends Composite
{
    public function __construct(private readonly Specification $inner)
    {
    }

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return !$this->inner->isSatisfiedBy($candidate);
    }
}
