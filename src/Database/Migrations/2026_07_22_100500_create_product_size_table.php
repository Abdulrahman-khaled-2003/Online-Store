<?php

namespace App\Database\Migrations;

use App\Core\Migration;

class CreateProductSizeTable extends Migration
{
    public function up(): void
    {
        db()->execute("
            CREATE TABLE product_size (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                size_id INT NOT NULL,
                FOREIGN KEY (product_id) REFERENCES products(id)
                    ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (size_id) REFERENCES sizes(id)
                    ON DELETE CASCADE ON UPDATE CASCADE
            )
        ");
    }

    public function down(): void
    {
        db()->execute("DROP TABLE IF EXISTS product_size");
    }
}