<?php

declare(strict_types=1);

namespace App\Specification;

abstract class Composite implements Specification
{
    public function and(Specification $other): Specification
    {
        return new AndSpec($this, $other);
    }

    public function or(Specification $other): Specification
    {
        return new OrSpec($this, $other);
    }

    public function not(): Specification
    {
        return new NotSpec($this);
    }
}
