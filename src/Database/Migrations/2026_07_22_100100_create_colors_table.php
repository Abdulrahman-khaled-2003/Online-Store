<?php

namespace App\Database\Migrations;

use App\Core\Migration;

class CreateColorsTable extends Migration
{
    public function up(): void
    {
        db()->execute("
            CREATE TABLE colors (
                id INT AUTO_INCREMENT PRIMARY KEY,
                colorName VARCHAR(255) NOT NULL
            )
        ");
    }

    public function down(): void
    {
        db()->execute("DROP TABLE IF EXISTS colors");
    }
}