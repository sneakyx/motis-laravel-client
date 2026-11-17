<?php

declare(strict_types=1);

return [
    'base_url' => env('MOTIS_BASE_URL', 'https://api.transitous.org'),

    'geocode_cache' => [
        'enabled' => true,

        // Cache store to use; null means the default store.
        'store' => null,

        // TTL in seconds; geocode results are fairly stable.
        'ttl' => 86400,
    ],
];
