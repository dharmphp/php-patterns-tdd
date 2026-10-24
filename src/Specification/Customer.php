<?php

declare(strict_types=1);

namespace App\Specification;

final class Customer
{
    public function __construct(
        public readonly string $name,
        public readonly bool $active,
        public readonly float $lifetimeSpend,
        public readonly string $country,
    ) {
    }
}
