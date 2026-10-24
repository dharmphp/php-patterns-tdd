<?php

declare(strict_types=1);

namespace App\Specification;

final class FromCountry extends Composite
{
    public function __construct(private readonly string $iso)
    {
    }

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $candidate instanceof Customer && $candidate->country === $this->iso;
    }
}
