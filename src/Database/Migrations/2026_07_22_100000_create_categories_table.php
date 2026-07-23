<?php

namespace App\Database\Migrations;

use App\Core\Migration;

class CreateCategoriesTable extends Migration{

    public function up() : void{
        db()->execute("
            CREATE TABLE categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                categoryName VARCHAR(255),
                categoryImage VARCHAR(255),
                categoryDescription VARCHAR(255)
            )
        ");
    }

    public function down(): void
    {
        db()->execute("DROP TABLE IF EXISTS categories");
    }
}