<?php

declare(strict_types=1);

namespace App\Observer;

final class HighValueAlert implements \SplObserver
{
    /** @var list<string> */
    public array $alerts = [];

    public function __construct(private readonly float $threshold)
    {
    }

    public function update(\SplSubject $subject): void
    {
        if ($subject instanceof Order && $subject->status() === 'paid' && $subject->total >= $this->threshold) {
            $this->alerts[] = $subject->id;
        }
    }
}
