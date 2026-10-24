# PHP Design Patterns, test-first

Modern PHP (8.2+, readonly properties, enums, match, first-class callables) exercising
the patterns I reach for most in Laravel and plain PHP services. Every class was written
red-green-refactor with PHPUnit 11.

| Pattern | Example |
|---|---|
| Factory | `ShapeFactory` with runtime registration |
| Observer | `Order` on `SplSubject`, audit log and threshold alert |
| Strategy | shipping cost by country |
| Decorator | notifier prefix, truncation, rate limit |
| Specification | composable customer rules |
| Pipeline / Middleware | immutable stage chain, onion middleware |
| Repository | in-memory users with uniqueness rule |
| Builder | fluent SQL builder with bindings |

```bash
composer install
composer test
```

Without a local PHP:

```bash
docker run --rm -v "$PWD":/app -w /app composer:2 install
docker run --rm -v "$PWD":/app -w /app php:8.3-cli vendor/bin/phpunit
```
