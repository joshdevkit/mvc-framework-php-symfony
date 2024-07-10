<?php

namespace App\Database\Migration;

interface MigrationInterface
{
    /**
     * Run the migration.
     *
     * @return void
     */
    public function up();

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down();
}
