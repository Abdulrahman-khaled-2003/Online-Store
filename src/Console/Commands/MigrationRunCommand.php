<?php

namespace App\Console\Commands;

use App\Core\MigrationRunner;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'migrate:run',
    description: 'Run all pending migrations.'
)]

class MigrationRunCommand extends Command{

#[Override]
	protected function execute(InputInterface $input, OutputInterface $output): int
    {
            $runner = new MigrationRunner();
            $runMigrations = $runner->run();
            $migExists = migrationsExists($runMigrations);
            if(! $migExists){
                $output->writeln("Not Found Migrations.!");
            }else{
                foreach($runMigrations as $migration){
                    $output->writeln("Migrated:" . $migration );
                }
            }
            return Command::SUCCESS;
    }
}