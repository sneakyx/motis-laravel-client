<?php

declare(strict_types=1);

namespace Sneakyx\MotisLaravelClient;

use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Support\Collection;
use Sneakyx\MotisPhpClient\Model\GeocodeMatch;
use Sneakyx\MotisPhpClient\Model\HealthResponse;
use Sneakyx\MotisPhpClient\MotisClient;

/**
 * Service object behind the Motis facade. Builds and caches the underlying
 * MotisClient from the Laravel configuration and adds Laravel-flavoured
 * helpers on top of the plain php client.
 */
final class MotisManager
{
    private ?MotisClient $client = null;

    public function __construct(
        private readonly ConfigRepository $config,
        private readonly CacheFactory $cache,
        private readonly ?MotisClient $injectedClient = null,
    ) {
    }

    /**
     * Returns the underlying php client.
     */
    public function client(): MotisClient
    {
        if ($this->injectedClient !== null) {
            return $this->injectedClient;
        }

        return $this->client ??= new MotisClient(
            (string) $this->config->get('motis.base_url', 'https://api.transitous.org'),
        );
    }

    /**
     * Geocodes a free-text query and returns the matches as a collection.
     *
     * Results are cached when the geocode cache is enabled.
     *
     * @param list<non-empty-string> $type
     * @return Collection<int, GeocodeMatch>
     */
    public function geocode(string $query, array $type = []): Collection
    {
        return new Collection($this->rememberGeocode($query, $type));
    }

    /**
     * Starts a fluent route search.
     */
    public function route(): RouteSearch
    {
        return new RouteSearch($this);
    }

    /**
     * Returns the health status of the configured MOTIS server.
     */
    public function health(): HealthResponse
    {
        return $this->client()->health();
    }

    /**
     * Fetches the geocode matches, through the cache when enabled.
     *
     * @param list<non-empty-string> $type
     * @return list<GeocodeMatch>
     */
    private function rememberGeocode(string $query, array $type): array
    {
        $cacheConfig = (array) $this->config->get('motis.geocode_cache', []);
        if (($cacheConfig['enabled'] ?? true) !== true) {
            return $this->client()->geocode($query, type: $type);
        }

        $key = 'motis:geocode:' . hash('sha256', $query . '|' . implode(',', $type));
        $store = $cacheConfig['store'] ?? null;
        $ttl = (int) ($cacheConfig['ttl'] ?? 86400);

        /** @var list<GeocodeMatch>|null $matches */
        $matches = $this->cache->store($store)->remember(
            $key,
            $ttl,
            fn (): array => $this->client()->geocode($query, type: $type),
        );

        return $matches ?? [];
    }
}
