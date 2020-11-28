<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Integration\MusementClient;
use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MusementClientTest extends TestCase
{
    private Client|MockObject $client;
    private MusementClient $musementClient;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->musementClient = new MusementClient($this->client);
    }

    /** @test */
    public function fetchCitiesShouldCallClientWithProperParams(): void
    {
        $this->client
            ->expects($this->once())
            ->method('get')
            ->with(
                'https://api.musement.com/api/v3/cities',
                ['Content-Type' => 'application/json']
            );

        $this->musementClient->fetchCities();
    }
}
