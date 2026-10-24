<?php

declare(strict_types=1);

namespace App\Decorator;

abstract class NotifierDecorator implements Notifier
{
    public function __construct(protected readonly Notifier $inner)
    {
    }

    public function send(string $to, string $message): string
    {
        return $this->inner->send($to, $message);
    }
}
