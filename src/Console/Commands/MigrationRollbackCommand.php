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
    name: "migrate:rollback",
    description: "Rollback the latest database migration batch."
)]

class MigrationRollbackCommand extends Command
{
    #[Override]
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $style = new SymfonyStyle($input, $output);

        try {
            $runner = new MigrationRunner();

            $rollBackMigrations = $runner->rollBack();

            $migExists = migrationsNotEmpty($rollBackMigrations);

            if (!$migExists) {
                $style->warning("Not Found Migrations.!");
            } else {
                foreach ($rollBackMigrations as $migration) {
                    $style->success("Rolled Back: " . $migration);
                }
            }
            return Command::SUCCESS;
        } catch (Exception $e) {
            $style->error($e->getMessage());
            errorLog($e->getMessage(), $e->getFile(), $e->getLine());
            return Command::FAILURE;
        }
    }
}
