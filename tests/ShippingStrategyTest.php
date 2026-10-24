<?php

declare(strict_types=1);

namespace Tests;

use App\Strategy\FlatRate;
use App\Strategy\FreeOver;
use App\Strategy\PerKilo;
use App\Strategy\ShippingCalculator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ShippingStrategyTest extends TestCase
{
    public function testFlatRateIgnoresInputs(): void
    {
        self::assertSame(7.0, (new FlatRate(7.0))->cost(99.0, 1.0));
    }

    public function testPerKiloRespectsMinimum(): void
    {
        $s = new PerKilo(2.0, 5.0);
        self::assertSame(5.0, $s->cost(1.0, 0.0));
        self::assertSame(8.0, $s->cost(4.0, 0.0));
    }

    public function testFreeOverThresholdDelegatesBelow(): void
    {
        $s = new FreeOver(50.0, new FlatRate(4.0));
        self::assertSame(4.0, $s->cost(1.0, 49.99));
        self::assertSame(0.0, $s->cost(1.0, 50.0));
    }

    public function testStrategyCanBeSwappedAtRuntime(): void
    {
        $calc = new ShippingCalculator(new FlatRate(10.0));
        self::assertSame(10.0, $calc->quote(1.0, 1.0));
        self::assertSame(3.0, $calc->using(new PerKilo(3.0))->quote(1.0, 1.0));
    }

    /** @return iterable<string, array{string, float, float, float}> */
    public static function countries(): iterable
    {
        yield 'india small' => ['in', 1.0, 500.0, 49.0];
        yield 'india free' => ['IN', 1.0, 999.0, 0.0];
        yield 'us light' => ['US', 1.0, 10.0, 5.0];
        yield 'us heavy' => ['US', 4.0, 10.0, 14.0];
        yield 'us free' => ['US', 4.0, 60.0, 0.0];
        yield 'rest of world' => ['DE', 1.0, 10.0, 15.0];
    }

    #[DataProvider('countries')]
    public function testCountryPresets(string $iso, float $kg, float $total, float $expected): void
    {
        self::assertSame($expected, ShippingCalculator::forCountry($iso)->quote($kg, $total));
    }
}
