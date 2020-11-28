<?php

declare(strict_types=1);

namespace App\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class MusementClient
{
    private const BASE_URI = 'https://api.musement.com/';
    private const URI_CITIES = 'api/v3/cities';

    private Client $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?? new Client([
            'http_errors' => false
        ]);
    }

    /** @throws GuzzleException */
    public function fetchCities(): ResponseInterface
    {
        return $this->client->get(self::BASE_URI . self::URI_CITIES, [
            'Content-Type' => 'application/json'
        ]);
    }
}
