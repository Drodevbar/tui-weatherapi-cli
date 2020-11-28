<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Builder\CityWithWeatherBuilder;
use App\DTO\CityWithCoordinatesDTO;
use App\DTO\CityWithWeatherDTO;
use App\DTO\TwoDaysForecastDTO;
use App\Exception\MissingCityWithWeatherException;
use App\Integration\MusementClient;
use App\Integration\WeatherClient;
use App\Tests\Util\WeatherApiDataProvider;
use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CityWithWeatherBuilderTest extends TestCase
{
    private MusementClient|MockObject $weatherClient;
    private CityWithWeatherBuilder $builder;
    private array $cityDeWallenData;
    private CityWithCoordinatesDTO $cityWithCoordinates;

    protected function setUp(): void
    {
        $this->weatherClient = $this->createMock(WeatherClient::class);
        $this->builder = new CityWithWeatherBuilder($this->weatherClient);
        $this->cityDeWallenData = json_decode(WeatherApiDataProvider::getDeWallenJsonData(), true);
        $this->cityWithCoordinates = CityWithCoordinatesDTO::fromArray([
            'name' => $this->cityDeWallenData['location']['name'],
            'latitude' => 0,
            'longitude' => 0
        ]);
    }

    /** @test */
    public function shouldThrowExceptionWhenStatusCodeIsDifferentThanOk(): void
    {
        $responseInterfaceMock = $this->createMock(ResponseInterface::class);
        $responseInterfaceMock
            ->method('getStatusCode')
            ->willReturn(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);

        $this->weatherClient
            ->expects($this->once())
            ->method('fetchWeatherForCityForTwoDays')
            ->with($this->cityWithCoordinates)
            ->willReturn($responseInterfaceMock);

        $this->expectException(MissingCityWithWeatherException::class);
        $this->expectExceptionMessage('Api responded with status different than 200');

        $this->builder->buildCityWithWeatherDTO($this->cityWithCoordinates);
    }

    /** @test */
    public function shouldThrowExceptionWhenGuzzleExceptionWasThrown(): void
    {
        $this->weatherClient
            ->expects($this->once())
            ->method('fetchWeatherForCityForTwoDays')
            ->with($this->cityWithCoordinates)
            ->willThrowException(new RequestException('Ooops!', new Request('GET', 'foo')));

        $this->expectException(MissingCityWithWeatherException::class);
        $this->expectExceptionMessage('Internal error: Ooops!');

        $this->builder->buildCityWithWeatherDTO($this->cityWithCoordinates);
    }

    /** @test */
    public function shouldBuildCityWithWeather(): void
    {
        $streamInterfaceMock = $this->createMock(StreamInterface::class);
        $streamInterfaceMock
            ->method('getContents')
            ->willReturn(WeatherApiDataProvider::getDeWallenJsonData());

        $responseInterfaceMock = $this->createMock(ResponseInterface::class);
        $responseInterfaceMock
            ->method('getBody')
            ->willReturn($streamInterfaceMock);
        $responseInterfaceMock
            ->method('getStatusCode')
            ->willReturn(StatusCodeInterface::STATUS_OK);

        $this->weatherClient
            ->expects($this->once())
            ->method('fetchWeatherForCityForTwoDays')
            ->with($this->cityWithCoordinates)
            ->willReturn($responseInterfaceMock);

        $result = $this->builder->buildCityWithWeatherDTO($this->cityWithCoordinates);

        $this->assertEquals(
            $result,
            CityWithWeatherDTO::fromCityWithCoordinatesAndTwoDaysForecast(
                $this->cityWithCoordinates,
                TwoDaysForecastDTO::fromArray($this->cityDeWallenData)
            )
        );
    }
}
