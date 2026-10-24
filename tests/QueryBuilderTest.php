<?php

declare(strict_types=1);

namespace Tests;

use App\Builder\QueryBuilder;
use PHPUnit\Framework\TestCase;

final class QueryBuilderTest extends TestCase
{
    public function testSelectStar(): void
    {
        self::assertSame('SELECT * FROM users', QueryBuilder::table('users')->toSql());
    }

    public function testWhereAndBindings(): void
    {
        $q = QueryBuilder::table('users')
            ->select('id', 'email')
            ->where('active', '=', 1)
            ->where('age', '>=', 18);

        self::assertSame('SELECT id, email FROM users WHERE active = ? AND age >= ?', $q->toSql());
        self::assertSame([1, 18], $q->bindings());
    }

    public function testWhereInExpandsPlaceholders(): void
    {
        $q = QueryBuilder::table('orders')->whereIn('status', ['paid', 'shipped']);

        self::assertSame('SELECT * FROM orders WHERE status IN (?, ?)', $q->toSql());
        self::assertSame(['paid', 'shipped'], $q->bindings());
    }

    public function testEmptyWhereInMatchesNothing(): void
    {
        self::assertSame('SELECT * FROM orders WHERE 1 = 0', QueryBuilder::table('orders')->whereIn('id', [])->toSql());
    }

    public function testOrderLimitOffset(): void
    {
        $q = QueryBuilder::table('posts')->orderBy('created_at', 'desc')->orderBy('id')->limit(10, 20);

        self::assertSame('SELECT * FROM posts ORDER BY created_at DESC, id ASC LIMIT 10 OFFSET 20', $q->toSql());
    }

    public function testLimitWithoutOffset(): void
    {
        self::assertSame('SELECT * FROM posts LIMIT 5', QueryBuilder::table('posts')->limit(5)->toSql());
    }
}
