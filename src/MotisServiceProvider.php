<?php

declare(strict_types=1);

namespace Sneakyx\MotisLaravelClient;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Sneakyx\MotisPhpClient\MotisClient;

/**
 * Registers the MOTIS integration in the Laravel service container.
 */
final class MotisServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/motis.php', 'motis');

        $this->app->singleton(MotisManager::class, static function (Application $app): MotisManager {
            /** @var \Illuminate\Contracts\Config\Repository $config */
            $config = $app->make('config');

            /** @var \Illuminate\Contracts\Cache\Factory $cache */
            $cache = $app->make('cache');

            return new MotisManager($config, $cache);
        });

        $this->app->singleton(MotisClient::class, static function (Application $app): MotisClient {
            return $app->make(MotisManager::class)->client();
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [__DIR__ . '/../config/motis.php' => config_path('motis.php')],
                'motis-config',
            );
        }
    }
}
