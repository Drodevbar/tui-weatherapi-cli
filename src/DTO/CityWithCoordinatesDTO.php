<?php

declare(strict_types=1);

namespace App\DTO;

final class CityWithCoordinatesDTO
{
    private string $name;
    private float $latitude;
    private float $longitude;

    private function __construct() {}

    public static function fromArray(array $parameters): self
    {
        $instance = new self();
        $instance->name = $parameters['name'];
        $instance->latitude = $parameters['latitude'];
        $instance->longitude = $parameters['longitude'];

        return $instance;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }
}
