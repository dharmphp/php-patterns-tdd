<?php

declare(strict_types=1);

namespace App\Repository;

final class DuplicateEmail extends \DomainException
{
    public function __construct(string $email)
    {
        parent::__construct("email already registered: $email");
    }
}
