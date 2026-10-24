<?php

declare(strict_types=1);

namespace Tests;

use App\Observer\AuditLog;
use App\Observer\HighValueAlert;
use App\Observer\Order;
use PHPUnit\Framework\TestCase;

final class OrderObserverTest extends TestCase
{
    public function testObserversAreNotifiedOnTransition(): void
    {
        $order = new Order('o-1', 120.0);
        $log = new AuditLog();
        $order->attach($log);

        $order->transition('paid');
        $order->transition('shipped');

        self::assertSame(['o-1 order.paid', 'o-1 order.shipped'], $log->entries);
    }

    public function testSameStatusDoesNotNotify(): void
    {
        $order = new Order('o-1', 10.0);
        $log = new AuditLog();
        $order->attach($log);

        $order->transition('new');

        self::assertSame([], $log->entries);
    }

    public function testDetachedObserverStopsReceiving(): void
    {
        $order = new Order('o-1', 10.0);
        $log = new AuditLog();
        $order->attach($log);
        $order->transition('paid');
        $order->detach($log);
        $order->transition('shipped');

        self::assertCount(1, $log->entries);
    }

    public function testHighValueAlertFiltersByStatusAndAmount(): void
    {
        $alert = new HighValueAlert(100.0);
        $small = new Order('small', 20.0);
        $big = new Order('big', 500.0);
        $small->attach($alert);
        $big->attach($alert);

        $small->transition('paid');
        $big->transition('packed');
        $big->transition('paid');

        self::assertSame(['big'], $alert->alerts);
    }
}
