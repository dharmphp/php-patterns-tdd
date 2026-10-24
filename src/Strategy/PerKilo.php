<?php

declare(strict_types=1);

namespace App\Strategy;

final class PerKilo implements ShippingStrategy
{
    public function __construct(private readonly float $perKg, private readonly float $minimum = 0.0)
    {
    }

    public function cost(float $weightKg, float $orderTotal): float
    {
        return max($this->minimum, round($weightKg * $this->perKg, 2));
    }
}
