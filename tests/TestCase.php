<?php

declare(strict_types=1);

namespace Sneakyx\MotisLaravelClient\Tests;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Sneakyx\MotisLaravelClient\MotisServiceProvider;
use Sneakyx\MotisLaravelClient\Tests\Support\MockHttpClient;
use Sneakyx\MotisPhpClient\MotisClient;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [MotisServiceProvider::class];
    }

    /**
     * Builds a real MotisClient on top of a mock PSR-18 client that replays
     * the given fixture files in order.
     *
     * @param list<string> $fixtures
     * @return array{0: MotisClient, 1: MockHttpClient}
     */
    protected function makeClient(array $fixtures): array
    {
        $responses = [];
        foreach ($fixtures as $fixture) {
            $body = (string) file_get_contents(__DIR__ . '/Fixtures/' . $fixture);
            $responses[] = new Response(200, ['Content-Type' => 'application/json'], $body);
        }

        $http = new MockHttpClient(...$responses);

        return [
            new MotisClient('https://motis.test', $http, new HttpFactory()),
            $http,
        ];
    }
}
