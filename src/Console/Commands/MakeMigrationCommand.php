<?php

namespace App\Console\Commands;

use App\Core\MigrationCreator;
use Exception;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $style = new SymfonyStyle($input, $output);

        try {
            $migrationName = $input->getArgument('migration-name');
            $num = $this->handleMakeCommand($migrationName, $input, $output);
            if ($num) {
                $makeMigration = new MigrationCreator($migrationName);
                $makeMigration->make();
                $style->success("Migration File: $migrationName is added Successfully To Migrations Directory");

                return Command::SUCCESS;
            }

            return Command::FAILURE;
        } catch (Exception $e) {
            errorLog($e->getMessage(), $e->getFile(), $e->getLine());
            $style->error($e->getMessage());
            return Command::FAILURE;
        }finally{
            db()->disConnect();
        }
    }

    private function handleMakeCommand(string $migrationName, InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);
        if (!preg_match('/^[a-z_][a-z0-9_]*$/', $migrationName)) {
            $style->error("Invalid Class Name: {$migrationName} \nShould start with a lowercase letter or underscore, followed by lowercase letters, numbers, or underscores only..!");
            return false;
        } elseif (! migrationFileExists($migrationName)) {
            $style->error("This migrationName exists: {$migrationName}. \nThe Migration File cannot be duplicated again!");
            return false;
        }
        return true;
    }
}
