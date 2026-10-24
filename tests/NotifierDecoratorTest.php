<?php

declare(strict_types=1);

namespace Tests;

use App\Decorator\PlainNotifier;
use App\Decorator\PrefixNotifier;
use App\Decorator\RateLimitedNotifier;
use App\Decorator\TruncatingNotifier;
use PHPUnit\Framework\TestCase;

final class NotifierDecoratorTest extends TestCase
{
    public function testPlainNotifier(): void
    {
        self::assertSame('to=a body=hi', (new PlainNotifier())->send('a', 'hi'));
    }

    public function testDecoratorsStackInOrder(): void
    {
        $n = new TruncatingNotifier(new PrefixNotifier(new PlainNotifier(), 'ALERT'), 5);

        self::assertSame('to=a body=[ALERT] 1234…', $n->send('a', '123456'));
    }

    public function testTruncationLeavesShortMessagesAlone(): void
    {
        $n = new TruncatingNotifier(new PlainNotifier(), 10);

        self::assertSame('to=a body=short', $n->send('a', 'short'));
    }

    public function testRateLimitPerRecipient(): void
    {
        $n = new RateLimitedNotifier(new PlainNotifier(), 2);
        $n->send('a', '1');
        $n->send('b', '1');
        $n->send('a', '2');

        $this->expectException(\RuntimeException::class);
        $n->send('a', '3');
    }
}
