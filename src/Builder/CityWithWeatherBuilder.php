<?php

declare(strict_types=1);

namespace App\Builder;

use App\DTO\CityWithCoordinatesDTO;
use App\DTO\CityWithWeatherDTO;
use App\DTO\TwoDaysForecastDTO;
use App\Exception\MissingCityWithWeatherException;
use App\Integration\WeatherClient;
use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Exception\GuzzleException;

class CityWithWeatherBuilder
{
    public function __construct(
        private WeatherClient $weatherClient
    ) {}

    /** @throws MissingCityWithWeatherException */
    public function buildCityWithWeatherDTO(CityWithCoordinatesDTO $cityWithCoordinatesDTO): CityWithWeatherDTO
    {
        try {
            $weatherResponse = $this->weatherClient->fetchWeatherForCityForTwoDays($cityWithCoordinatesDTO);
            if ($weatherResponse->getStatusCode() !== StatusCodeInterface::STATUS_OK) {
                throw new MissingCityWithWeatherException('Api responded with status different than 200');
            }

            $twoDaysForecastDTO = TwoDaysForecastDTO::fromArray(
                json_decode($weatherResponse->getBody()->getContents(), true)
            );

            return CityWithWeatherDTO::fromCityWithCoordinatesAndTwoDaysForecast(
              $cityWithCoordinatesDTO,
              $twoDaysForecastDTO
            );
        } catch (GuzzleException $e) {
            throw new MissingCityWithWeatherException('Internal error: ' . $e->getMessage());
        }
    }
}
