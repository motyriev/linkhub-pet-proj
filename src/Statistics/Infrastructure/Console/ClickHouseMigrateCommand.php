<?php

declare(strict_types=1);

namespace App\Statistics\Infrastructure\Console;

use App\Statistics\Infrastructure\Database\ClickHouseMigration;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:clickhouse:migrate')]
class ClickHouseMigrateCommand extends Command
{
    private ClickHouseMigration $clickHouseMigration;

    public function __construct(ClickHouseMigration $clickHouseMigration)
    {
        parent::__construct();
        $this->clickHouseMigration = $clickHouseMigration;
    }

    protected function configure(): void
    {
        $this->addOption('rollback', null, InputOption::VALUE_NONE);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('rollback')) {
            $this->clickHouseMigration->rollback();
            $output->writeln('ClickHouse rollback completed successfully');
        } else {
            $this->clickHouseMigration->migrate();
            $output->writeln('ClickHouse migration completed successfully');
        }

        return Command::SUCCESS;
    }
}
