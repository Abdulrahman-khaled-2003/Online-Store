<?php

namespace App\Core;

class MigrationRunner{
    public function __construct(protected $migrationsPath){
        //
        $this->createMigrationsTableIfNotExists();
        }

    protected function createMigrationsTableIfNotExists() : void{
        db()->execute("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration_name VARCHAR(255) NOT NULL,
                batch INT NOT NULL
            )
        ");
    }

    protected function getRanMigrations(): array
{
    $rows = db()->fetchAll("SELECT migration_name FROM migrations");
    return array_column($rows, 'migration_name');
}
}