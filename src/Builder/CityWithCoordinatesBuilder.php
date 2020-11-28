<?php

declare(strict_types=1);

namespace App\Builder;

use App\DTO\CityWithCoordinatesDTO;
use App\Exception\MissingCitiesWithCoordinatesException;
use App\Integration\MusementClient;
use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Exception\GuzzleException;

class CityWithCoordinatesBuilder
{
    public function __construct(
        private MusementClient $musementClient,
    ) {}

    /**
     * @return CityWithCoordinatesDTO[]
     * @throws MissingCitiesWithCoordinatesException
     */
    public function buildCitiesWithCoordinatesDTOs(): array
    {
        try {
            $citiesResponse = $this->musementClient->fetchCities();
            if ($citiesResponse->getStatusCode() !== StatusCodeInterface::STATUS_OK) {
                throw new MissingCitiesWithCoordinatesException('Api responded with status different than 200');
            }

            return array_map(
                static fn(array $cityData) => CityWithCoordinatesDTO::fromArray($cityData),
                json_decode($citiesResponse->getBody()->getContents(), true)
            );

        } catch (GuzzleException $e) {
            throw new MissingCitiesWithCoordinatesException('Internal error: ' . $e->getMessage());
        }
    }
}
