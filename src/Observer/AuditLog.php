<?php

declare(strict_types=1);

namespace App\Observer;

final class AuditLog implements \SplObserver
{
    /** @var list<string> */
    public array $entries = [];

    public function update(\SplSubject $subject): void
    {
        if ($subject instanceof Order) {
            $this->entries[] = "{$subject->id} {$subject->lastEvent}";
        }
    }
}
