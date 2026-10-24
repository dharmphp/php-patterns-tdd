<?php

declare(strict_types=1);

namespace App\Strategy;

final class FlatRate implements ShippingStrategy
{
    public function __construct(private readonly float $rate)
    {
    }

    public function cost(float $weightKg, float $orderTotal): float
    {
        return $this->rate;
    }
}
