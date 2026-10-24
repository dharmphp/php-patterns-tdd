<?php

declare(strict_types=1);

namespace App\Strategy;

final class ShippingCalculator
{
    public function __construct(private ShippingStrategy $strategy)
    {
    }

    public function using(ShippingStrategy $strategy): self
    {
        $this->strategy = $strategy;

        return $this;
    }

    public function quote(float $weightKg, float $orderTotal): float
    {
        return $this->strategy->cost($weightKg, $orderTotal);
    }

    public static function forCountry(string $iso): self
    {
        return new self(match (strtoupper($iso)) {
            'IN' => new FreeOver(999.0, new FlatRate(49.0)),
            'US' => new FreeOver(50.0, new PerKilo(3.5, 5.0)),
            default => new PerKilo(9.0, 15.0),
        });
    }
}
