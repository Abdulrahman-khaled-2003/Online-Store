<?php

namespace App\Console\Commands;

use App\Core\MigrationRunner;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: "migrate:rollback",
    description: "Rollback the latest database migration batch."
)]

class MigrationRollbackCommand extends Command
{
    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $runner = new MigrationRunner();
        $rollBackMigrations = $runner->rollBack();
        $migExists = migrationsExists($rollBackMigrations);
        if (! $migExists) {
            $output->writeln("Not Found Migrations.!");
        } else {
            foreach ($rollBackMigrations as $migration) {
                $output->writeln("Rolled Back:" . $migration);
            }
        }
        return Command::SUCCESS;
    }
}
