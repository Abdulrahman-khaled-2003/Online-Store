<?php

namespace App\Core;

use App\Core\Exceptions\DirectoryNotFoundException;

class MigrationCreator
{
    private string $migrationFilePath;
    private string  $contentOfMigrationFile;

    public function __construct(protected ?string $migrationName, protected string $migrationsPath)
    {
        //
    }

    public function make()
    {
        $this->createMigrationFileWithContent($this->migrationName);
        file_put_contents($this->migrationFilePath, $this->contentOfMigrationFile);
    }

    private function createMigrationFileWithContent(string $migrationName){   
            $migrationFileName = date("Y_m_d_His") . "_{$this->migrationName}";
            $this->migrationFilePath = $this->migrationsPath . "/" . $migrationFileName . ".php";
            $className = resolveClassName($this->migrationName);
            $this->contentOfMigrationFile =  <<<PHP
<?php
        
namespace App\Database\Migrations;

use App\Core\Migration;

class {$className} extends Migration
{
    public function up(): void {}
    public function down(): void {}
}
PHP;           
            }
    }