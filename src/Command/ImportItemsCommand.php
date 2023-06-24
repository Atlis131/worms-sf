<?php

namespace App\Command;

use App\Service\ImportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use DateTime;

class ImportItemsCommand extends Command
{
    private ImportService $importService;

    public function __construct(
        ImportService $importService
    )
    {
        $this->importService = $importService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:import')
            ->setDescription('Import items to database')
            ->setHelp('Import weapons to database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $startTime = new DateTime('now');

        $io = new SymfonyStyle($input, $output);
        $io->writeln([
            " > Started processing weapons. " . $startTime->format("Y-m-d H:i:s")
        ]);

        $this->importService->import();

        $endTime = new DateTime('now');
        $io->writeln([
            " > Ended processing weapons. " . $endTime->format("Y-m-d H:i:s")
        ]);

        return Command::SUCCESS;
    }
}