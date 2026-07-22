<?php

namespace App\Database\Migrations;

use App\Core\Migration;

class CreateProductColorTable extends Migration
{
    public function up(): void
    {
        db()->execute("
            CREATE TABLE product_color (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                color_id INT NOT NULL,
                FOREIGN KEY (product_id) REFERENCES products(id)
                    ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (color_id) REFERENCES colors(id)
                    ON DELETE CASCADE ON UPDATE CASCADE
            )
        ");
    }

    public function down(): void
    {
        db()->execute("DROP TABLE IF EXISTS product_color");
    }
}