<?php

declare(strict_types=1);

namespace App\Decorator;

final class PrefixNotifier extends NotifierDecorator
{
    public function __construct(Notifier $inner, private readonly string $prefix)
    {
        parent::__construct($inner);
    }

    public function send(string $to, string $message): string
    {
        return parent::send($to, "[{$this->prefix}] $message");
    }
}
