<?php

declare(strict_types=1);

namespace App\Service;

use App\Builder\CityWithCoordinatesBuilder;
use App\Builder\CityWithWeatherBuilder;
use App\Exception\MissingCitiesWithCoordinatesException;
use App\Exception\MissingCityWithWeatherException;
use Symfony\Component\Console\Output\OutputInterface;

class CityWeatherPrinter
{
    public function __construct(
        private CityWithCoordinatesBuilder $cityWithCoordinatesBuilder,
        private CityWithWeatherBuilder $cityWithWeatherBuilder
    ) {}

    public function listAllCitiesWithWeatherDetails(OutputInterface $output): void
    {
        try {
            $citiesWithCoordinates = $this->cityWithCoordinatesBuilder->buildCitiesWithCoordinatesDTOs();

            foreach ($citiesWithCoordinates as $city) {
                try {
                    $cityWithWeather = $this->cityWithWeatherBuilder->buildCityWithWeatherDTO($city);

                    $output->writeln($cityWithWeather);
                } catch (MissingCityWithWeatherException) {
                    $output->writeln('Skipping ' . $city->getName() . ' - cannot fetch city weather details');
                }
            }
        } catch (MissingCitiesWithCoordinatesException $e) {
            $output->writeln('Skipping - cannot fetch cities. Error details: ' . $e->getMessage());
        }
    }
}
