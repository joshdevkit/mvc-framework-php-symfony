<?php

namespace App\Schema;

class Blueprint
{
    protected $table;
    protected $columns = [];
    protected $foreignKeys = [];
    protected $indexes = [];

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function id()
    {
        $this->columns[] = "id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY";
    }

    public function string($name, $default = null)
    {
        $columnDefinition = "{$name} VARCHAR(255) NOT NULL";
        if ($default !== null) {
            $columnDefinition .= " DEFAULT '{$default}'";
        }
        $this->columns[] = $columnDefinition;
    }

    public function longText($name)
    {
        $this->columns[] = "{$name} LONGTEXT NOT NULL";
    }

    public function timestamps()
    {
        $this->columns[] = "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
    }

    public function bigInteger($column)
    {
        $this->columns[] = "{$column} BIGINT";
    }

    public function unsignedBigInteger($column)
    {
        $columnName = "{$column}";
        $this->columns[] = "{$columnName} BIGINT UNSIGNED NOT NULL";
        $this->indexes[] = "INDEX idx_{$this->table}_{$columnName} ({$columnName})";
        return new ForeignKeyDefinitionBuilder($this, $columnName);
    }

    public function foreign($localColumn)
    {
        return new ForeignKeyDefinitionBuilder($this, $localColumn);
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getIndexes()
    {
        return $this->indexes;
    }

    public function addForeignKey(ForeignKeyDefinition $foreignKey)
    {
        $this->foreignKeys[] = $foreignKey;
    }

    public function getForeignKeys()
    {
        return $this->foreignKeys;
    }
}
