<?php

namespace App\Console\Commands;

use App\Core\MigrationRunner;
use Exception;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'migrate:run',
    description: 'Run all pending migrations.'
)]

class MigrationRunCommand extends Command
{
    #[Override]
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $style = new SymfonyStyle($input, $output);

        try {
            $runner = new MigrationRunner();

            $runMigrations = $runner->run();

            $migExists = migrationsNotEmpty($runMigrations);

            if (!$migExists) {
                $style->warning("Not Found Migrations.!");
            } else {
                foreach ($runMigrations as $migration) {
                    $style->success("Migrated: " . $migration);
                }
            }

            return Command::SUCCESS;
        } catch (Exception $e) {
            $style->error($e->getMessage());

            return Command::FAILURE;
        }finally{
            db()->disConnect();
        }
    }
}
