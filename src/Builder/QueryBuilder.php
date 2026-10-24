<?php

declare(strict_types=1);

namespace App\Builder;

final class QueryBuilder
{
    /** @var list<string> */
    private array $columns = ['*'];
    /** @var list<string> */
    private array $wheres = [];
    /** @var list<mixed> */
    private array $bindings = [];
    /** @var list<string> */
    private array $orders = [];
    private ?int $limit = null;
    private ?int $offset = null;

    private function __construct(private readonly string $table)
    {
    }

    public static function table(string $table): self
    {
        return new self($table);
    }

    public function select(string ...$columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function where(string $column, string $op, mixed $value): self
    {
        $this->wheres[] = "$column $op ?";
        $this->bindings[] = $value;

        return $this;
    }

    public function whereIn(string $column, array $values): self
    {
        if ($values === []) {
            $this->wheres[] = '1 = 0';

            return $this;
        }
        $marks = implode(', ', array_fill(0, count($values), '?'));
        $this->wheres[] = "$column IN ($marks)";
        array_push($this->bindings, ...array_values($values));

        return $this;
    }

    public function orderBy(string $column, string $dir = 'asc'): self
    {
        $dir = strtolower($dir) === 'desc' ? 'DESC' : 'ASC';
        $this->orders[] = "$column $dir";

        return $this;
    }

    public function limit(int $n, int $offset = 0): self
    {
        $this->limit = $n;
        $this->offset = $offset ?: null;

        return $this;
    }

    public function toSql(): string
    {
        $sql = sprintf('SELECT %s FROM %s', implode(', ', $this->columns), $this->table);
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        if ($this->orders) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orders);
        }
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }
        if ($this->offset !== null) {
            $sql .= " OFFSET {$this->offset}";
        }

        return $sql;
    }

    /** @return list<mixed> */
    public function bindings(): array
    {
        return $this->bindings;
    }
}
