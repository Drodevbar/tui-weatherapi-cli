<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\DTO\CityWithCoordinatesDTO;
use App\Integration\WeatherClient;
use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class WeatherClientTest extends TestCase
{
    private const ENV_WEATHER_API_KEY = 'weather-api-key';

    private Client|MockObject $client;
    private WeatherClient $weatherClient;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->weatherClient = new WeatherClient($this->client);
        $_ENV['WEATHER_API_KEY'] = self::ENV_WEATHER_API_KEY;
    }

    /** @test */
    public function fetchWeatherForCityForTwoDaysShouldCallClientWithProperParams(): void
    {
        $cityWithCoordinates = CityWithCoordinatesDTO::fromArray([
            'name' => 'foo',
            'latitude' => 1.0,
            'longitude' => 2.005
        ]);

        $expectedUri = implode('', [
            'https://api.weatherapi.com/v1/forecast.json',
            '?key=' . self::ENV_WEATHER_API_KEY,
            '&q=' . $cityWithCoordinates->getLatitude() . ',' . $cityWithCoordinates->getLongitude(),
            '&days=2'
        ]);

        $this->client
            ->expects($this->once())
            ->method('get')
            ->with($expectedUri);

        $this->weatherClient->fetchWeatherForCityForTwoDays($cityWithCoordinates);
    }
}
