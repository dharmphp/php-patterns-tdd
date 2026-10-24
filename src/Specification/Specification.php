<?php

declare(strict_types=1);

namespace App\Specification;

interface Specification
{
    public function isSatisfiedBy(mixed $candidate): bool;
}
