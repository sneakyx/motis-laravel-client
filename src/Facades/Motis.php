<?php

declare(strict_types=1);

namespace Sneakyx\MotisLaravelClient\Facades;

use Illuminate\Support\Facades\Facade;
use Sneakyx\MotisLaravelClient\MotisManager;
use Sneakyx\MotisPhpClient\MotisClient;
use Sneakyx\MotisPhpClient\Model\GeocodeMatch;
use Sneakyx\MotisPhpClient\Model\HealthResponse;
use Illuminate\Support\Collection;

/**
 * Facade for the MOTIS integration.
 *
 * @method static MotisClient client()
 * @method static Collection<int, GeocodeMatch> geocode(string $query, array $type = [])
 * @method static \Sneakyx\MotisLaravelClient\RouteSearch route()
 * @method static HealthResponse health()
 *
 * @see MotisManager
 */
final class Motis extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MotisManager::class;
    }
}
