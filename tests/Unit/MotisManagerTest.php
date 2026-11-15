<?php

declare(strict_types=1);

namespace Sneakyx\MotisLaravelClient\Tests\Unit;

use Illuminate\Contracts\Cache\Factory;
use Illuminate\Support\Collection;
use Sneakyx\MotisLaravelClient\MotisManager;
use Sneakyx\MotisLaravelClient\Tests\TestCase;
use Sneakyx\MotisPhpClient\Model\GeocodeMatch;
use Sneakyx\MotisPhpClient\MotisClient;

final class MotisManagerTest extends TestCase
{
    public function testClientIsBuiltWhenNoClientIsInjected(): void
    {
        config(['motis.base_url' => 'https://motis.example.com']);

        $manager = new MotisManager(
            config(),
            $this->app->make(Factory::class),
        );

        self::assertInstanceOf(MotisClient::class, $manager->client());
    }

    public function testGeocodeReturnsMatchesAsCollection(): void
    {
        [$client, $http] = $this->makeClient(['geocode_response.json']);
        $manager = new MotisManager(config(), $this->app->make(Factory::class), $client);

        $result = $manager->geocode('Hauptwache');

        self::assertInstanceOf(Collection::class, $result);
        self::assertGreaterThan(0, $result->count());
        self::assertInstanceOf(GeocodeMatch::class, $result->first());
        self::assertSame('Frankfurt (Main) Hauptwache', $result->first()->getName());
        self::assertCount(1, $http->requests());
    }

    public function testGeocodeResultsAreCached(): void
    {
        // Only one response is configured: a second live request would throw.
        [$client, $http] = $this->makeClient(['geocode_response.json']);
        $manager = new MotisManager(config(), $this->app->make(Factory::class), $client);

        $first = $manager->geocode('Hauptwache');
        $second = $manager->geocode('Hauptwache');

        self::assertSame($first->count(), $second->count());
        self::assertCount(1, $http->requests());
    }

    public function testGeocodeBypassesCacheWhenDisabled(): void
    {
        config(['motis.geocode_cache.enabled' => false]);

        [$client, $http] = $this->makeClient(['geocode_response.json', 'geocode_response.json']);
        $manager = new MotisManager(config(), $this->app->make(Factory::class), $client);

        $manager->geocode('Hauptwache');
        $manager->geocode('Hauptwache');

        self::assertCount(2, $http->requests());
    }
}
