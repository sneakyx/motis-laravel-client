<?php

declare(strict_types=1);

namespace Sneakyx\MotisLaravelClient\Tests\Unit;

use Illuminate\Contracts\Cache\Factory;
use Illuminate\Support\Collection;
use Sneakyx\MotisLaravelClient\Facades\Motis;
use Sneakyx\MotisLaravelClient\MotisManager;
use Sneakyx\MotisLaravelClient\RouteSearch;
use Sneakyx\MotisLaravelClient\Tests\TestCase;
use Sneakyx\MotisPhpClient\MotisClient;

final class FacadeTest extends TestCase
{
    public function testFacadeResolvesTheManager(): void
    {
        [$client] = $this->makeClient([]);

        $this->app->instance(
            MotisManager::class,
            new MotisManager(config(), $this->app->make(Factory::class), $client),
        );

        self::assertInstanceOf(RouteSearch::class, Motis::route());
        self::assertInstanceOf(MotisClient::class, Motis::client());
    }

    public function testFacadeGeocodeReturnsACollection(): void
    {
        [$client] = $this->makeClient(['geocode_response.json']);

        $this->app->instance(
            MotisManager::class,
            new MotisManager(config(), $this->app->make(Factory::class), $client),
        );

        self::assertInstanceOf(Collection::class, Motis::geocode('Hauptwache'));
    }
}
