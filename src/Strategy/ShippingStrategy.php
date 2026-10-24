<?php

declare(strict_types=1);

namespace App\Strategy;

interface ShippingStrategy
{
    public function cost(float $weightKg, float $orderTotal): float;
}
