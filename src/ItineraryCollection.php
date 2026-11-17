<?php

declare(strict_types=1);

namespace Sneakyx\MotisLaravelClient;

use Illuminate\Support\Collection;
use Sneakyx\MotisPhpClient\Model\Itinerary;

/**
 * Collection of itineraries with convenience helpers.
 *
 * @template-extends Collection<int, Itinerary>
 */
final class ItineraryCollection extends Collection
{
    /**
     * Returns the itinerary with the shortest duration.
     */
    public function fastest(): ?Itinerary
    {
        return $this->sortBy(
            static fn (Itinerary $itinerary): int => (int) ($itinerary->getDuration() ?? PHP_INT_MAX),
        )->first();
    }
}
