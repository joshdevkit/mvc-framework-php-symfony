<?php

namespace App\Database\Eloquent;

use PDO;

class QueryBuilder
{
    protected $pdo;
    public $table;
    protected $bindings = [];
    protected $sql;
    protected $wheres = [];

    public function __construct(PDO $pdo, $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->sql = "SELECT * FROM {$table}";
    }

    public function join($table, $first, $operator, $second)
    {
        $this->sql .= " JOIN {$table} ON {$first} {$operator} {$second}";
        return $this;
    }

    public function where($column, $operator, $value)
    {
        $this->wheres[] = "{$column} {$operator} ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function get()
    {
        if (!empty($this->wheres)) {
            $this->sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        $stmt = $this->pdo->prepare($this->sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }
}
