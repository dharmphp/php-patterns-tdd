<?php

declare(strict_types=1);

namespace Tests;

use App\Pipeline\Middleware;
use App\Pipeline\Pipeline;
use PHPUnit\Framework\TestCase;

final class PipelineTest extends TestCase
{
    public function testStagesRunInOrder(): void
    {
        $p = Pipeline::make()
            ->pipe('trim')
            ->pipe('strtolower')
            ->pipe(fn (string $s) => str_replace(' ', '-', $s));

        self::assertSame('hello-world', $p->process('  Hello World '));
        self::assertSame('a-b', $p('A B'));
    }

    public function testPipelineIsImmutable(): void
    {
        $base = Pipeline::make()->pipe(fn (int $n) => $n + 1);
        $doubled = $base->pipe(fn (int $n) => $n * 2);

        self::assertSame(2, $base->process(1));
        self::assertSame(4, $doubled->process(1));
    }

    public function testEmptyPipelineIsIdentity(): void
    {
        self::assertSame(['x'], Pipeline::make()->process(['x']));
    }

    public function testMiddlewareWrapsCore(): void
    {
        $trace = [];
        $mw = (new Middleware())
            ->add(function (array $req, callable $next) use (&$trace) {
                $trace[] = 'auth in';
                $res = $next($req + ['user' => 'u1']);
                $trace[] = 'auth out';

                return $res;
            })
            ->add(function (array $req, callable $next) use (&$trace) {
                $trace[] = 'log in';
                $res = $next($req);
                $trace[] = 'log out';

                return $res + ['logged' => true];
            });

        $res = $mw->handle(['path' => '/'], fn (array $req) => ['status' => 200, 'user' => $req['user']]);

        self::assertSame(['auth in', 'log in', 'log out', 'auth out'], $trace);
        self::assertSame(['status' => 200, 'user' => 'u1', 'logged' => true], $res);
    }

    public function testMiddlewareCanShortCircuit(): void
    {
        $mw = (new Middleware())->add(fn (array $req, callable $next) => $req['ok'] ? $next($req) : ['status' => 403]);

        self::assertSame(['status' => 403], $mw->handle(['ok' => false], fn () => ['status' => 200]));
        self::assertSame(['status' => 200], $mw->handle(['ok' => true], fn () => ['status' => 200]));
    }
}
