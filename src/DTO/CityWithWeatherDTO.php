<?php

declare(strict_types=1);

namespace App\DTO;

use Stringable;

final class CityWithWeatherDTO implements Stringable
{
    private CityWithCoordinatesDTO $cityWithCoordinatesDTO;
    private TwoDaysForecastDTO $twoDaysForecastDTO;

    public function __construct() {}

    public static function fromCityWithCoordinatesAndTwoDaysForecast(
        CityWithCoordinatesDTO $cityWithCoordinatesDTO,
        TwoDaysForecastDTO $twoDaysForecastDTO
    ): self {
        $instance = new self();
        $instance->cityWithCoordinatesDTO = $cityWithCoordinatesDTO;
        $instance->twoDaysForecastDTO = $twoDaysForecastDTO;

        return $instance;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s | %s - %s',
            $this->cityWithCoordinatesDTO->getName(),
            $this->twoDaysForecastDTO->getTodayConditionText(),
            $this->twoDaysForecastDTO->getTomorrowConditionText()
        );
    }
}
