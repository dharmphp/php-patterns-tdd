<?php

declare(strict_types=1);

namespace App\Decorator;

final class RateLimitedNotifier extends NotifierDecorator
{
    /** @var array<string, int> */
    private array $sent = [];

    public function __construct(Notifier $inner, private readonly int $maxPerRecipient)
    {
        parent::__construct($inner);
    }

    public function send(string $to, string $message): string
    {
        $this->sent[$to] = ($this->sent[$to] ?? 0) + 1;
        if ($this->sent[$to] > $this->maxPerRecipient) {
            throw new \RuntimeException("rate limit hit for $to");
        }

        return parent::send($to, $message);
    }
}
