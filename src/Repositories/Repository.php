<?php

namespace App\Repositories;

use PDO;
use PDOStatement;

abstract class Repository
{
    protected string $table;

    public function __construct(
        protected PDO $pdo
    ) {}

    protected function exec(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    protected function query(string $sql, array $params = []): array
    {
        return $this->exec($sql, $params)->fetchAll();
    }

    protected function queryOne(string $sql, array $params = []): ?array
    {
        return $this->exec($sql, $params)->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $table = $this->table;

        $fields = implode(', ', array_keys($data));
        $questionMarks = $this->generateQuestionMarks(count($data));

        $sql = "INSERT INTO $table ($fields) VALUES ($questionMarks)";
        $params = array_values($data);

        $this->exec($sql, $params);

        return (int) $this->pdo->lastInsertId();
    }

    private function select(array $fields): array
    {
        $table = $this->table;

        [$whereClause, $params] = $this->buildWhereClause($fields);

        $sql = "SELECT * FROM $table WHERE $whereClause";

        return [$sql, $params];
    }

    public function find(array $fields): array
    {
        return $this->query(...$this->select($fields));
    }

    public function findOne(array $fields): ?array
    {
        return $this->queryOne(...$this->select($fields));
    }

    public function update(array $fields, array $data): void
    {
        $table = $this->table;

        [$setClause, $setParams] = $this->buildSetClause($data);
        [$whereClause, $whereParams] = $this->buildWhereClause($fields);

        $sql = "UPDATE $table SET $setClause WHERE $whereClause";
        $params = [...$setParams, ...$whereParams];

        $this->exec($sql, $params);
    }

    public function delete(array $fields): void
    {
        $table = $this->table;

        [$whereClause, $params] = $this->buildWhereClause($fields);

        $sql = "DELETE FROM $table WHERE $whereClause";

        $this->exec($sql, $params);
    }

    public function count(array $filters = []): int
    {
        $table = $this->table;

        $sql = "SELECT COUNT(*) FROM $table";
        $params = [];

        if ($filters) {
            [$whereClause, $whereParams] = $this->buildWhereClause($filters);
            $sql .= " WHERE $whereClause";
            $params = array_merge($params, $whereParams);
        }

        return (int) $this->exec($sql, $params)->fetchColumn();
    }

    public function getPaginated(int $limit, int $offset, array $filters = []): array
    {
        $table = $this->table;

        $sql = "SELECT * FROM $table";
        $params = [];

        if ($filters) {
            [$whereClause, $whereParams] = $this->buildWhereClause($filters);
            $sql .= " WHERE $whereClause";
            $params = array_merge($params, $whereParams);
        }

        $sql .=  " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        return $this->query($sql, $params);
    }

    private function buildSetClause(array $data): array
    {
        $sql = implode(', ', array_map(fn($field) => "$field = ?", array_keys($data)));
        $params = array_values($data);

        return [$sql, $params];
    }

    private function buildWhereClause(array $filters): array
    {
        $conditions = [];
        $params = [];

        foreach ($filters as $field => $value) {
            if ($field === 'name') {
                $conditions[] = "$field LIKE ?";
                $params[] = "%$value%";

                continue;
            }

            $conditions[] = "$field = ?";
            $params[] = $value;
        }

        $sql = implode(' AND ', $conditions);

        return [$sql, $params];
    }

    private function generateQuestionMarks(int $count): string
    {
        return implode(', ', array_fill(0, $count, '?'));
    }
}