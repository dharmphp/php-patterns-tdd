<?php

declare(strict_types=1);

namespace App\Factory;

final class ShapeFactory
{
    /** @var array<string, callable(array<string, float>): Shape> */
    private array $makers = [];

    public function __construct()
    {
        $this->register('circle', fn (array $a) => new Circle($a['radius']));
        $this->register('rectangle', fn (array $a) => new Rectangle($a['width'], $a['height']));
        $this->register('square', fn (array $a) => new Rectangle($a['side'], $a['side']));
    }

    public function register(string $type, callable $maker): void
    {
        $this->makers[$type] = $maker;
    }

    /** @param array<string, float> $args */
    public function make(string $type, array $args = []): Shape
    {
        if (!isset($this->makers[$type])) {
            throw new \InvalidArgumentException("unknown shape: $type");
        }

        return ($this->makers[$type])($args);
    }

    /** @param array<array{type: string, args?: array<string, float>}> $specs */
    public function makeMany(array $specs): array
    {
        return array_map(fn ($s) => $this->make($s['type'], $s['args'] ?? []), $specs);
    }
}
