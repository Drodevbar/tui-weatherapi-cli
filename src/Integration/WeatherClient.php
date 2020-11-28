<?php

declare(strict_types=1);

namespace App\Integration;

use App\DTO\CityWithCoordinatesDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class WeatherClient
{
    private const BASE_URI = 'https://api.weatherapi.com/';
    private const URI_FORECAST = 'v1/forecast.json';

    private const QUERY_PARAM_KEY = 'key';
    private const QUERY_PARAM_LAT_LONG = 'q';
    private const QUERY_PARAM_DAYS = 'days';

    private Client $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?? new Client([
            'http_errors' => false
        ]);
    }

    /** @throws GuzzleException */
    public function fetchWeatherForCityForTwoDays(CityWithCoordinatesDTO $city): ResponseInterface
    {
        $queryParamKey = '?' . self::QUERY_PARAM_KEY . '=' . $_ENV['WEATHER_API_KEY'];
        $queryParamLatLong = '&' . self::QUERY_PARAM_LAT_LONG . '=' . $this->getLatLongForCity($city);
        $queryParamDays = '&' . self::QUERY_PARAM_DAYS . '=2';

        $uri = implode('', [
            self::BASE_URI,
            self::URI_FORECAST,
            $queryParamKey,
            $queryParamLatLong,
            $queryParamDays
        ]);

        return $this->client->get($uri);
    }

    private function getLatLongForCity(CityWithCoordinatesDTO $city): string
    {
        return $city->getLatitude() . ',' . $city->getLongitude();
    }
}
