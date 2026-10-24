<?php

declare(strict_types=1);

namespace Tests;

use App\Specification\Customer;
use App\Specification\FromCountry;
use App\Specification\IsActive;
use App\Specification\SpentAtLeast;
use PHPUnit\Framework\TestCase;

final class SpecificationTest extends TestCase
{
    private Customer $ann;
    private Customer $bob;
    private Customer $cho;

    protected function setUp(): void
    {
        $this->ann = new Customer('ann', true, 1200.0, 'IN');
        $this->bob = new Customer('bob', false, 5000.0, 'US');
        $this->cho = new Customer('cho', true, 50.0, 'US');
    }

    public function testLeafSpecs(): void
    {
        self::assertTrue((new IsActive())->isSatisfiedBy($this->ann));
        self::assertFalse((new IsActive())->isSatisfiedBy($this->bob));
        self::assertTrue((new SpentAtLeast(1000.0))->isSatisfiedBy($this->bob));
        self::assertFalse((new FromCountry('IN'))->isSatisfiedBy($this->cho));
    }

    public function testAndOrNotCompose(): void
    {
        $vip = (new IsActive())->and(new SpentAtLeast(1000.0));
        $usOrInactive = (new FromCountry('US'))->or((new IsActive())->not());

        self::assertTrue($vip->isSatisfiedBy($this->ann));
        self::assertFalse($vip->isSatisfiedBy($this->bob));
        self::assertFalse($vip->isSatisfiedBy($this->cho));

        self::assertFalse($usOrInactive->isSatisfiedBy($this->ann));
        self::assertTrue($usOrInactive->isSatisfiedBy($this->bob));
        self::assertTrue($usOrInactive->isSatisfiedBy($this->cho));
    }

    public function testSpecDrivesFiltering(): void
    {
        $spec = (new IsActive())->and((new SpentAtLeast(1000.0))->not());
        $names = array_map(
            fn (Customer $c) => $c->name,
            array_values(array_filter([$this->ann, $this->bob, $this->cho], [$spec, 'isSatisfiedBy'])),
        );

        self::assertSame(['cho'], $names);
    }

    public function testNonCustomerNeverSatisfies(): void
    {
        self::assertFalse((new IsActive())->isSatisfiedBy('nope'));
        self::assertTrue((new IsActive())->not()->isSatisfiedBy(null));
    }
}
