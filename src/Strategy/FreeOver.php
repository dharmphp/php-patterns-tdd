<?php

declare(strict_types=1);

namespace App\Strategy;

final class FreeOver implements ShippingStrategy
{
    public function __construct(private readonly float $threshold, private readonly ShippingStrategy $otherwise)
    {
    }

    public function cost(float $weightKg, float $orderTotal): float
    {
        return $orderTotal >= $this->threshold ? 0.0 : $this->otherwise->cost($weightKg, $orderTotal);
    }
}
