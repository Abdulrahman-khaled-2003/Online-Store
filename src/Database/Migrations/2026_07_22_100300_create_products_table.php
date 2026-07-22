<?php

namespace App\Database\Migrations;

use App\Core\Migration;

class CreateProductsTable extends Migration
{
    public function up(): void
    {
        db()->execute("
            CREATE TABLE products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                productImage VARCHAR(255),
                productPrice FLOAT,
                productName VARCHAR(255),
                category_id INT,
                productDescription VARCHAR(255),
                FOREIGN KEY (category_id) REFERENCES categories(id)
                    ON DELETE CASCADE ON UPDATE CASCADE
            )
        ");
    }

    public function down(): void
    {
        db()->execute("DROP TABLE IF EXISTS products");
    }
}