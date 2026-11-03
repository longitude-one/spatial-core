<?php
/**
 * This file is part of the LongitudeOne Spatial core library.
 *
 * PHP 8.3 | 8.4 | 8.5
 *
 * Copyright LongitudeOne - Alexandre Tranchant.
 * Copyright 2026.
 *
 */

declare(strict_types=1);

namespace LongitudeOne\Core\Tests\Unit\Enum;

use LongitudeOne\Core\Enum\AxisEnum;
use PHPUnit\Framework\TestCase;

/**
 * Describes the geographic conventions carried by each coordinate axis.
 *
 * @internal
 *
 * @coversNothing
 */
final class AxisEnumTest extends TestCase
{
    /**
     * Tests that each geographic axis identifies the complementary axis.
     */
    public function testAxesCompleteEachOther(): void
    {
        // A geographic position always combines one north-south and one east-west axis.
        static::assertSame(AxisEnum::LONGITUDE, AxisEnum::LATITUDE->other());
        static::assertSame(AxisEnum::LATITUDE, AxisEnum::LONGITUDE->other());
    }

    /**
     * Tests latitude's geographic conventions.
     */
    public function testLatitudeConventions(): void
    {
        $latitude = AxisEnum::LATITUDE;

        // Latitude describes the north-south position on the globe.
        static::assertSame('lat', $latitude->abbreviation());
        static::assertSame('N', $latitude->positiveCardinal());
        static::assertSame('S', $latitude->negativeCardinal());

        // A latitude cannot be farther than 90 degrees from the equator.
        static::assertSame(90, $latitude->rangeLimit());
    }

    /**
     * Tests longitude's geographic conventions.
     */
    public function testLongitudeConventions(): void
    {
        $longitude = AxisEnum::LONGITUDE;

        // Longitude describes the east-west position around the globe.
        static::assertSame('lon', $longitude->abbreviation());
        static::assertSame('E', $longitude->positiveCardinal());
        static::assertSame('W', $longitude->negativeCardinal());

        // A longitude cannot be farther than 180 degrees from the prime meridian.
        static::assertSame(180, $longitude->rangeLimit());
    }
}
