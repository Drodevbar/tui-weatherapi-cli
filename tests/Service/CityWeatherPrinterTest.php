<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Builder\CityWithCoordinatesBuilder;
use App\Builder\CityWithWeatherBuilder;
use App\DTO\CityWithCoordinatesDTO;
use App\DTO\CityWithWeatherDTO;
use App\DTO\TwoDaysForecastDTO;
use App\Exception\MissingCitiesWithCoordinatesException;
use App\Exception\MissingCityWithWeatherException;
use App\Service\CityWeatherPrinter;
use App\Tests\Util\MusementApiDataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

class CityWeatherPrinterTest extends TestCase
{
    private CityWithCoordinatesBuilder|MockObject $cityWithCoordinatesBuilder;
    private CityWithWeatherBuilder|MockObject $cityWithWeatherBuilder;
    private OutputInterface|MockObject $outputInterface;
    private CityWeatherPrinter $printer;
    private array $citiesWithCoordinatesData;

    protected function setUp(): void
    {
        $this->cityWithCoordinatesBuilder = $this->createMock(CityWithCoordinatesBuilder::class);
        $this->cityWithWeatherBuilder = $this->createMock(CityWithWeatherBuilder::class);
        $this->outputInterface = $this->createMock(OutputInterface::class);
        $this->printer = new CityWeatherPrinter(
            $this->cityWithCoordinatesBuilder,
            $this->cityWithWeatherBuilder
        );
        $this->citiesWithCoordinatesData = json_decode(MusementApiDataProvider::getCitiesJsonData(), true);
    }

    /** @test */
    public function shouldWriteErrorMessageWhenMissingCitiesWithCoordinatesExceptionThrown(): void
    {
        $this->cityWithCoordinatesBuilder
            ->expects($this->once())
            ->method('buildCitiesWithCoordinatesDTOs')
            ->willThrowException(new MissingCitiesWithCoordinatesException('Ooops!'));

        $this->outputInterface
            ->expects($this->once())
            ->method('writeln')
            ->with('Skipping - cannot fetch cities. Error details: Ooops!');

        $this->printer->listAllCitiesWithWeatherDetails($this->outputInterface);
    }

    /** @test */
    public function shouldWriteErrorMessageWhenMissingCityWithWeatherExceptionThrown(): void
    {
        /** @var CityWithCoordinatesDTO[] $cityWithCoordinatesDTOs */
        $cityWithCoordinatesDTOs = [CityWithCoordinatesDTO::fromArray($this->citiesWithCoordinatesData[0])];

        $this->cityWithCoordinatesBuilder
            ->expects($this->once())
            ->method('buildCitiesWithCoordinatesDTOs')
            ->willReturn($cityWithCoordinatesDTOs);

        $this->cityWithWeatherBuilder
            ->expects($this->once())
            ->method('buildCityWithWeatherDTO')
            ->with($cityWithCoordinatesDTOs[0])
            ->willThrowException(new MissingCityWithWeatherException('Ooops!'));

        $this->outputInterface
            ->expects($this->once())
            ->method('writeln')
            ->with('Skipping ' . $cityWithCoordinatesDTOs[0]->getName() . ' - cannot fetch city weather details');

        $this->printer->listAllCitiesWithWeatherDetails($this->outputInterface);
    }

    /** @test */
    public function shouldListAllCitiesWithWeatherDetails(): void
    {
        $citiesWithCoordinatesDTOs = [
            CityWithCoordinatesDTO::fromArray($this->citiesWithCoordinatesData[0]),
            CityWithCoordinatesDTO::fromArray($this->citiesWithCoordinatesData[1])
        ];

        $this->cityWithCoordinatesBuilder
            ->expects($this->once())
            ->method('buildCitiesWithCoordinatesDTOs')
            ->willReturn($citiesWithCoordinatesDTOs);

        $citiesWithWeatherDetailsDTOs = [
            CityWithWeatherDTO::fromCityWithCoordinatesAndTwoDaysForecast(
                $citiesWithCoordinatesDTOs[0],
                TwoDaysForecastDTO::fromArray($this->citiesWithCoordinatesData[0])
            ),
            CityWithWeatherDTO::fromCityWithCoordinatesAndTwoDaysForecast(
                $citiesWithCoordinatesDTOs[1],
                TwoDaysForecastDTO::fromArray($this->citiesWithCoordinatesData[1])
            )
        ];

        $this->cityWithWeatherBuilder
            ->expects($this->exactly(2))
            ->method('buildCityWithWeatherDTO')
            ->withConsecutive([$citiesWithCoordinatesDTOs[0]], [$citiesWithCoordinatesDTOs[1]])
            ->willReturnOnConsecutiveCalls(...$citiesWithWeatherDetailsDTOs);

        $this->outputInterface
            ->expects($this->exactly(2))
            ->method('writeln')
            ->withConsecutive([$citiesWithWeatherDetailsDTOs[0]], [$citiesWithWeatherDetailsDTOs[1]]);

        $this->printer->listAllCitiesWithWeatherDetails($this->outputInterface);
    }
}
