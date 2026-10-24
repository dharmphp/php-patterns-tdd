<?php

declare(strict_types=1);

namespace App\Factory;

interface Shape
{
    public function area(): float;

    public function name(): string;
}
