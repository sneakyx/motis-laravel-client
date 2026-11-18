# motis-laravel-client

A Laravel integration for [MOTIS](https://motis-project.de), built on top of
[sneakyx/motis-php-client](https://github.com/sneakyx/motis-php-client). It adds
Laravel-flavoured ergonomics to the plain php client: a facade, configuration,
caching and collections with convenience helpers.

## Features

- Facade `Motis` backed by a service singleton
- Fluent route search with automatic geocoding of place names:
  `Motis::route()->from('Aachen Hbf')->to('Aachen Rothe Erde')->at($time)->get()`
- Support for arrive-by searches
- Laravel collections as return values, with helpers like `fastest()`
- Geocode results cached through the Laravel cache
- Direct DI of the underlying `MotisClient` stays possible

## Requirements

- PHP 8.2+
- Laravel 12 or 13
- A MOTIS server (e.g. https://api.transitous.org or your own instance)

## Installation

Until the packages are published on Packagist, add the repositories to your
composer.json:

    "repositories": [
        {"type": "vcs", "url": "https://github.com/sneakyx/motis-php-client.git"},
        {"type": "vcs", "url": "https://github.com/sneakyx/motis-laravel-client.git"}
    ]

Then run:

    composer require sneakyx/motis-laravel-client

Publish the configuration:

    php artisan vendor:publish --provider="Sneakyx\MotisLaravelClient\MotisServiceProvider" --tag=motis-config

## Configuration

`config/motis.php`:

- `base_url`: base URL of the MOTIS server, defaults to `MOTIS_BASE_URL` or https://api.transitous.org
- `geocode_cache.enabled`: whether geocode results are cached (default `true`)
- `geocode_cache.store`: cache store to use (`null` = default store)
- `geocode_cache.ttl`: cache TTL in seconds (default one day)

## Quick start

Route search with place names (geocoded automatically):

    use Sneakyx\MotisLaravelClient\Facades\Motis;

    $itineraries = Motis::route()
        ->from('Aachen Hbf')
        ->to('Aachen Rothe Erde')
        ->at('2026-11-18T10:00:00+01:00')
        ->get();

    $fastest = $itineraries->fastest();

Search for connections arriving in time:

    Motis::route()->from('Aachen Hbf')->to('Aachen Rothe Erde')->arriveBy()->at($arrival)->get();

Coordinates can be passed as `"lat,lon"` strings or as `GeocodeMatch` objects
(e.g. the first result of `Motis::geocode('Aachen Hbf')`).

## Versioning

This package stays in sync with motis-php-client: same major.minor line,
starting at 0.1.0.

## Testing

    composer install
    composer test

## License

MIT. See [LICENSE](LICENSE).
