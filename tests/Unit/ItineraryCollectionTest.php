<?php

declare(strict_types=1);

namespace Sneakyx\MotisLaravelClient\Tests\Unit;

use Sneakyx\MotisLaravelClient\ItineraryCollection;
use Sneakyx\MotisLaravelClient\Tests\TestCase;
use Sneakyx\MotisPhpClient\Model\Itinerary;

final class ItineraryCollectionTest extends TestCase
{
    public function testFastestReturnsTheItineraryWithTheShortestDuration(): void
    {
        $slow = new Itinerary(['duration' => 600]);
        $fast = new Itinerary(['duration' => 300]);

        $collection = new ItineraryCollection([$slow, $fast]);

        self::assertSame($fast, $collection->fastest());
    }

    public function testFastestReturnsNullForAnEmptyCollection(): void
    {
        self::assertNull((new ItineraryCollection([]))->fastest());
    }

    public function testFastestTreatsNullDurationAsSlowest(): void
    {
        $withoutDuration = new Itinerary([]);
        $slow = new Itinerary(['duration' => 900]);

        $collection = new ItineraryCollection([$withoutDuration, $slow]);

        self::assertSame($slow, $collection->fastest());
    }
}
