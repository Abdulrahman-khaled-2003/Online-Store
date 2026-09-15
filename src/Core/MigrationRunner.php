<?php

namespace App\Core;

use App\Core\Exceptions\FileNotFoundException;

class MigrationRunner
{
    public function __construct(protected string $migrationsPath = "src/Database/Migrations")
    {
        //
        $this->createMigrationsTableIfNotExists();
    }

    protected function createMigrationsTableIfNotExists(): void
    {
        db()->execute("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration_name VARCHAR(255) NOT NULL UNIQUE,
                batch INT NOT NULL
            )
        ");
    }

    protected function getRanMigrations(): array
    {
        $rows = db()->fetchAll("SELECT migration_name FROM migrations");
        return array_column($rows, 'migration_name');
    }

    protected function getNextBatchNumber(): int
    {
        $result = db()->fetch("SELECT MAX(batch) as max_batch FROM migrations");
        return ($result['max_batch'] ?? 0) + 1;
    }

    public function run()
    {
        $ran = $this->getRanMigrations();
        $files = glob($this->migrationsPath . '/*.php');
        sort($files);

        $batch = $this->getNextBatchNumber();
        $executedAny = false;

        foreach ($files as $file) {
            $migrationName = basename($file, '.php');

            if (in_array($migrationName, $ran)) {
                continue;
            }
            if (! file_exists($file)) {
                throw new FileNotFoundException("File Not Found!");
            }
            require_once $file;

            $className = resolveClassName($migrationName);
            $fullClassName = "App\\Database\\Migrations\\$className";

            $migration = new $fullClassName();
            $migration->up();

            db()->execute(
                "INSERT INTO migrations (migration_name, batch) VALUES (?, ?)",
                [$migrationName, $batch]
            );
            $migrations[] = $migrationName; 
            $executedAny = true;
            }
        if (!$executedAny) {
            echo "Not Found Any New Migration \n";
        }else{
            return $migrations;
        }
    }

    protected function getLastBatchNumber()
    {
        $result = db()->fetch("SELECT MAX(batch) as max_batch FROM migrations");
        return $result['max_batch'] ?? 0;
    }

    protected function getMigrationsInBatch(int $batch)
    {
        $rows = db()->fetchAll(
            "SELECT migration_name FROM migrations WHERE batch = ? ORDER BY id DESC",
            [$batch]
        );
        return array_column($rows, 'migration_name');
    }

    public function rollBack()
    {
        $batch = $this->getLastBatchNumber();

        if ($batch === 0) {
            return;
            }

        $migrations = $this->getMigrationsInBatch($batch);

        foreach ($migrations as $migrationName) {

            $file = $this->migrationsPath . "/" . $migrationName . ".php";

            if (! file_exists($file)) {
                throw new FileNotFoundException("File Not Found!");
            }
            require_once $file;

            $className = resolveClassName($migrationName);
            $allClassName = "App\\Database\\Migrations\\$className";

            $migration = new $allClassName();
            $migration->down();

            db()->execute(
                "DELETE FROM migrations WHERE migration_name = ?",
                [$migrationName]
            );

            echo "Rolled back: $migrationName\n";
        }
    }
}
