<?php

declare(strict_types=1);

namespace App\Specification;

final class IsActive extends Composite
{
    public function isSatisfiedBy(mixed $candidate): bool
    {
        return $candidate instanceof Customer && $candidate->active;
    }
}
