<?php

namespace App\Support\Facades;

use App\Schema\Blueprint;
use PDO;

class Schema
{
    protected static $pdo;

    /**
     * Set the PDO instance.
     *
     * @param PDO $pdo
     */
    public static function setPDO(PDO $pdo)
    {
        static::$pdo = $pdo;
    }

    /**
     * Execute a raw SQL query.
     *
     * @param string $sql
     * @return bool
     */
    public static function raw($sql)
    {
        if (!static::$pdo) {
            throw new \RuntimeException('PDO connection not set.');
        }

        $stmt = static::$pdo->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Drop a table if it exists.
     *
     * @param string $table
     * @return void
     */
    public static function dropIfExists($table)
    {
        $sql = "DROP TABLE IF EXISTS {$table}";
        static::raw($sql);
    }

    /**
     * Create a new table schema.
     *
     * @param string $table
     * @param \Closure $callback
     * @return void
     */
    public static function create($table, \Closure $callback)
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);

        // Get columns, indexes, and foreign keys defined in Blueprint
        $columns = $blueprint->getColumns();
        $indexes = $blueprint->getIndexes();
        $foreignKeys = $blueprint->getForeignKeys();

        // Create SQL statement for table creation
        $sql = "CREATE TABLE IF NOT EXISTS {$table} (";
        $sql .= implode(', ', $columns);

        if (!empty($indexes)) {
            $sql .= ', ' . implode(', ', $indexes);
        }

        if (!empty($foreignKeys)) {
            $sql .= ', ' . implode(', ', array_map(function ($fk) {
                return $fk->getDefinition();
            }, $foreignKeys));
        }

        $sql .= ") ENGINE=InnoDB;";

        static::raw($sql);
    }

    /**
     * Create a new table schema statically.
     *
     * @param string $table
     * @param \Closure $callback
     * @return void
     */
    public static function table($table, \Closure $callback)
    {
        static::create($table, $callback);
    }
}
