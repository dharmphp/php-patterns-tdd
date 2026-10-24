<?php

declare(strict_types=1);

namespace Tests;

use App\Factory\Circle;
use App\Factory\Rectangle;
use App\Factory\Shape;
use App\Factory\ShapeFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ShapeFactoryTest extends TestCase
{
    private ShapeFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new ShapeFactory();
    }

    /** @return iterable<string, array{string, array<string, float>, class-string, float}> */
    public static function shapes(): iterable
    {
        yield 'circle' => ['circle', ['radius' => 1.0], Circle::class, M_PI];
        yield 'rectangle' => ['rectangle', ['width' => 2.0, 'height' => 3.0], Rectangle::class, 6.0];
        yield 'square' => ['square', ['side' => 4.0], Rectangle::class, 16.0];
    }

    #[DataProvider('shapes')]
    public function testMakesRegisteredShapes(string $type, array $args, string $class, float $area): void
    {
        $shape = $this->factory->make($type, $args);

        self::assertInstanceOf($class, $shape);
        self::assertEqualsWithDelta($area, $shape->area(), 0.0001);
    }

    public function testUnknownTypeFails(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('unknown shape: hexagon');

        $this->factory->make('hexagon');
    }

    public function testInvalidDimensionsFail(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->factory->make('circle', ['radius' => -1.0]);
    }

    public function testCanRegisterNewShapeAtRuntime(): void
    {
        $this->factory->register('unit', fn () => new class implements Shape {
            public function area(): float
            {
                return 1.0;
            }

            public function name(): string
            {
                return 'unit';
            }
        });

        self::assertSame(1.0, $this->factory->make('unit')->area());
    }

    public function testMakeManySumsAreas(): void
    {
        $shapes = $this->factory->makeMany([
            ['type' => 'square', 'args' => ['side' => 2.0]],
            ['type' => 'rectangle', 'args' => ['width' => 1.0, 'height' => 5.0]],
        ]);

        self::assertSame(9.0, array_sum(array_map(fn (Shape $s) => $s->area(), $shapes)));
    }
}
