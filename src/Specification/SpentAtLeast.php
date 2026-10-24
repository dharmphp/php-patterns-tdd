<?php

declare(strict_types=1);

namespace App\Specification;

final class SpentAtLeast extends Composite
{
    public function __construct(private readonly float $amount)
    {
    }

    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $candidate instanceof Customer && $candidate->lifetimeSpend >= $this->amount;
    }
}
