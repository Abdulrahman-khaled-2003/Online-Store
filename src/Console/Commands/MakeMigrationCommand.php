<?php

namespace App\Console\Commands;

use App\Core\MigrationCreator;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: "migrate:make",
    description: "make migration"
)]

class MakeMigrationCommand extends Command
{

    #[Override]
    protected function configure()
    {
        $this->addArgument("migration-name", InputArgument::REQUIRED, "The name of the Migration");
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $migrationName = $input->getArgument('migration-name');
        $num = $this->handleMakeCommand($migrationName, $output);
        if ($num == 0) {
            $makeMigration = new MigrationCreator($migrationName, "src/Database/Migrations");
            $makeMigration->make();
            $output->writeln("Migration File: $migrationName is added Successfully To Migrations Directory");
            return Command::SUCCESS;
        }
        return Command::FAILURE;
    }

    private function handleMakeCommand(string $migrationName, OutputInterface $output): int
    {
        if(!preg_match('/^[a-z_][a-z0-9_]*$/', $migrationName)) {
            $output->writeLn("Invalid Class Name: {$migrationName} \nShould start with a lowercase letter or underscore, followed by lowercase letters, numbers, or underscores only..!");
            return Command::FAILURE;
        } elseif (! migrationFileExists($migrationName, "src/Database/Migrations")) {
            $output->writeln("This migrationName exists: {$migrationName}. \nThe Migration File cannot be duplicated again!");
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
