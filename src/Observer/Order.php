<?php

declare(strict_types=1);

namespace App\Observer;

final class Order implements \SplSubject
{
    private \SplObjectStorage $observers;
    private string $status = 'new';
    public ?string $lastEvent = null;

    public function __construct(public readonly string $id, public readonly float $total)
    {
        $this->observers = new \SplObjectStorage();
    }

    public function attach(\SplObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(\SplObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function status(): string
    {
        return $this->status;
    }

    public function transition(string $status): void
    {
        if ($status === $this->status) {
            return;
        }
        $this->status = $status;
        $this->lastEvent = "order.$status";
        $this->notify();
    }
}
