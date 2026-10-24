<?php

declare(strict_types=1);

namespace Tests;

use App\Repository\DuplicateEmail;
use App\Repository\InMemoryUserRepository;
use App\Repository\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

final class InMemoryUserRepositoryTest extends TestCase
{
    private UserRepository $repo;

    protected function setUp(): void
    {
        $this->repo = new InMemoryUserRepository();
    }

    public function testSaveAssignsIncrementingIds(): void
    {
        $a = $this->repo->save(new User(null, 'a@x.io', 'A'));
        $b = $this->repo->save(new User(null, 'b@x.io', 'B'));

        self::assertSame(1, $a->id);
        self::assertSame(2, $b->id);
        self::assertSame('A', $this->repo->find(1)?->name);
    }

    public function testEmailLookupIsCaseInsensitive(): void
    {
        $this->repo->save(new User(null, 'Ann@X.io', 'A'));

        self::assertNotNull($this->repo->findByEmail('ann@x.io'));
        self::assertNull($this->repo->findByEmail('nobody@x.io'));
    }

    public function testDuplicateEmailIsRejected(): void
    {
        $this->repo->save(new User(null, 'a@x.io', 'A'));

        $this->expectException(DuplicateEmail::class);
        $this->repo->save(new User(null, 'A@x.io', 'Other'));
    }

    public function testUpdatingSameUserKeepsEmail(): void
    {
        $saved = $this->repo->save(new User(null, 'a@x.io', 'A'));
        $this->repo->save($saved->deactivated());

        self::assertFalse($this->repo->find($saved->id)?->active);
        self::assertSame([], $this->repo->active());
    }

    public function testDeleteAndActiveFilter(): void
    {
        $a = $this->repo->save(new User(null, 'a@x.io', 'A'));
        $this->repo->save(new User(null, 'b@x.io', 'B'));
        $this->repo->delete($a->id);

        self::assertNull($this->repo->find($a->id));
        self::assertSame(['B'], array_map(fn (User $u) => $u->name, $this->repo->active()));
    }
}
