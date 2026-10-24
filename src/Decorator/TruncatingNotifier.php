<?php

declare(strict_types=1);

namespace App\Decorator;

final class TruncatingNotifier extends NotifierDecorator
{
    public function __construct(Notifier $inner, private readonly int $limit)
    {
        parent::__construct($inner);
    }

    public function send(string $to, string $message): string
    {
        if (mb_strlen($message) > $this->limit) {
            $message = mb_substr($message, 0, $this->limit - 1) . '…';
        }

        return parent::send($to, $message);
    }
}
