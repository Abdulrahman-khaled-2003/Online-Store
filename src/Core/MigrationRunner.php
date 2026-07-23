<?php

namespace App\Core;

class MigrationRunner
{
    public function __construct(protected $migrationsPath)
    {
        //
        $this->createMigrationsTableIfNotExists();
    }

    protected function createMigrationsTableIfNotExists(): void
    {
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

    protected function getNextBatchNumber(): int
    {
        $result = db()->fetch("SELECT MAX(batch) as max_batch FROM migrations");
        return ($result['max_batch'] ?? 0) + 1;
    }

    protected function resolveClassName(string $migrationName): string
    {
        $withoutTimestamp = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $migrationName);
        $words = explode('_', $withoutTimestamp);
        $words = array_map('ucfirst', $words);

        return implode('', $words);
    }

    public function run(): void
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

        require_once $file;

        $className = $this->resolveClassName($migrationName);
        $fullClassName = "App\\Database\\Migrations\\$className";

        $migration = new $fullClassName();
        $migration->up();

        db()->execute(
            "INSERT INTO migrations (migration_name, batch) VALUES (?, ?)",
            [$migrationName, $batch]
        );

        echo "Migrated: $migrationName\n";
        $executedAny = true;
    }

    if (!$executedAny) {
        echo "Not Found Any New Migration \n";
    }
}
}
