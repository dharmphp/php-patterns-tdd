<?php

declare(strict_types=1);

namespace App\Decorator;

final class PlainNotifier implements Notifier
{
    public function send(string $to, string $message): string
    {
        return "to=$to body=$message";
    }
}
