<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Builder\CityWithCoordinatesBuilder;
use App\DTO\CityWithCoordinatesDTO;
use App\Exception\MissingCitiesWithCoordinatesException;
use App\Integration\MusementClient;
use App\Tests\Util\MusementApiDataProvider;
use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CityWithCoordinatesBuilderTest extends TestCase
{
    private MusementClient|MockObject $musementClient;
    private CityWithCoordinatesBuilder $builder;

    protected function setUp(): void
    {
        $this->musementClient = $this->createMock(MusementClient::class);
        $this->builder = new CityWithCoordinatesBuilder($this->musementClient);
    }

    /** @test */
    public function shouldThrowExceptionWhenStatusCodeIsDifferentThanOk(): void
    {
        $responseInterfaceMock = $this->createMock(ResponseInterface::class);
        $responseInterfaceMock
            ->method('getStatusCode')
            ->willReturn(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);

        $this->musementClient
            ->expects($this->once())
            ->method('fetchCities')
            ->willReturn($responseInterfaceMock);

        $this->expectException(MissingCitiesWithCoordinatesException::class);
        $this->expectExceptionMessage('Api responded with status different than 200');

        $this->builder->buildCitiesWithCoordinatesDTOs();
    }

    /** @test */
    public function shouldThrowExceptionWhenGuzzleExceptionWasThrown(): void
    {
        $this->musementClient
            ->expects($this->once())
            ->method('fetchCities')
            ->willThrowException(new RequestException('Ooops!', new Request('GET', 'foo')));

        $this->expectException(MissingCitiesWithCoordinatesException::class);
        $this->expectExceptionMessage('Internal error: Ooops!');

        $this->builder->buildCitiesWithCoordinatesDTOs();
    }

    /** @test */
    public function shouldBuildCitiesWithCoordinates(): void
    {
        $citiesJsonData = MusementApiDataProvider::getCitiesJsonData();

        $streamInterfaceMock = $this->createMock(StreamInterface::class);
        $streamInterfaceMock
            ->method('getContents')
            ->willReturn($citiesJsonData);

        $responseInterfaceMock = $this->createMock(ResponseInterface::class);
        $responseInterfaceMock
            ->method('getBody')
            ->willReturn($streamInterfaceMock);
        $responseInterfaceMock
            ->method('getStatusCode')
            ->willReturn(StatusCodeInterface::STATUS_OK);

        $this->musementClient
            ->expects($this->once())
            ->method('fetchCities')
            ->willReturn($responseInterfaceMock);

        $result = $this->builder->buildCitiesWithCoordinatesDTOs();

        $citiesJsonArray = json_decode($citiesJsonData, true);

        $this->assertEquals(
            $result[0],
            CityWithCoordinatesDTO::fromArray($citiesJsonArray[0])
        );
        $this->assertEquals(
            $result[1],
            CityWithCoordinatesDTO::fromArray($citiesJsonArray[1])
        );
    }
}
