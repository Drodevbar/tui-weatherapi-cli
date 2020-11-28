<?php

declare(strict_types=1);

namespace App\DTO;

final class TwoDaysForecastDTO
{
    private const KEY_FORECAST = 'forecast';
    private const KEY_FORECAST_DAY = 'forecastday';
    private const KEY_DAY = 'day';
    private const KEY_CONDITION = 'condition';
    private const KEY_TEXT = 'text';

    private string $todayConditionText;
    private string $tomorrowConditionText;

    private function __construct() {}

    public static function fromArray(array $parameters): self
    {
        $instance = new self();
        $forecastData = $parameters[self::KEY_FORECAST][self::KEY_FORECAST_DAY] ?? [];
        $instance->todayConditionText = $forecastData[0][self::KEY_DAY][self::KEY_CONDITION][self::KEY_TEXT] ?? '';
        $instance->tomorrowConditionText = $forecastData[1][self::KEY_DAY][self::KEY_CONDITION][self::KEY_TEXT] ?? '';

        return $instance;
    }

    public function getTodayConditionText(): string
    {
        return $this->todayConditionText;
    }

    public function getTomorrowConditionText(): string
    {
        return $this->tomorrowConditionText;
    }
}
