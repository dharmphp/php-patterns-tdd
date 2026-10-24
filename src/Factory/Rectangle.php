<?php

declare(strict_types=1);

namespace App\Factory;

final class Rectangle implements Shape
{
    public function __construct(private readonly float $width, private readonly float $height)
    {
        if ($width <= 0 || $height <= 0) {
            throw new \InvalidArgumentException('sides must be positive');
        }
    }

    public function area(): float
    {
        return $this->width * $this->height;
    }

    public function name(): string
    {
        return 'rectangle';
    }
}
