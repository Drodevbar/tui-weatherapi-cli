<?php

declare(strict_types=1);

namespace App\Command;

use App\Kernel\DependencyInjection;
use App\Service\CityWeatherPrinter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WeatherCommand extends Command
{
    protected static $defaultName = 'app:check-weather';

    protected function configure(): void
    {
        $this
            ->setDescription('Checks weather for each city available in Musement\'s catalogue');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Starting processing cities...');

        $printerService = DependencyInjection::getContainerBuilder()->get(CityWeatherPrinter::class);
        $printerService->listAllCitiesWithWeatherDetails($output);

        $output->writeln('That\'s it. See you!');

        return Command::SUCCESS;
    }
}
