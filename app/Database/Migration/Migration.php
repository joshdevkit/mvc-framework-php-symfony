<?php

namespace App\Database\Migration;

use PDO;
use App\Schema\Blueprint;

class Migration implements MigrationInterface
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Run the migration.
     *
     * @return void
     */
    public function up()
    {
        // Implement migration logic here
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        // Implement rollback logic here
    }

    /**
     * Execute SQL query.
     *
     * @param string $sql
     * @return void
     */
    protected function execute($sql)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    /**
     * Create a new database table.
     *
     * @param string $tableName
     * @param \Closure $callback
     * @return void
     */
    protected function createTable($tableName, \Closure $callback)
    {
        $blueprint = new Blueprint($tableName);
        $callback($blueprint);

        $columns = implode(", ", $blueprint->getColumns());
        $sql = "CREATE TABLE IF NOT EXISTS {$tableName} ({$columns})";

        $this->execute($sql);
    }

    /**
     * Run a single migration class.
     *
     * @param string $migrationClass
     * @return void
     */
    protected function runMigration($migrationClass)
    {
        require_once "App/Database/Migration/{$migrationClass}.php";

        $migration = new $migrationClass($this->pdo);

        if ($migration instanceof MigrationInterface) {
            $migration->up();
            echo "Migrated: {$migrationClass}\n";
        } else {
            echo "Invalid migration class: {$migrationClass}. Skipping.\n";
        }
    }
}

?>
