<?php

declare(strict_types=1);

namespace App\Factory;

final class Circle implements Shape
{
    public function __construct(private readonly float $radius)
    {
        if ($radius <= 0) {
            throw new \InvalidArgumentException('radius must be positive');
        }
    }

    public function area(): float
    {
        return M_PI * $this->radius ** 2;
    }

    public function name(): string
    {
        return 'circle';
    }
}
