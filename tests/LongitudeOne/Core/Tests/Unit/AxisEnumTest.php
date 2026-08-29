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

namespace LongitudeOne\Core\Tests\Unit;

use LongitudeOne\Core\AxisEnum;
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
    public function testAxesCompleteEachOther(): void
    {
        // A geographic position always combines one north-south and one east-west axis.
        self::assertSame(AxisEnum::LONGITUDE, AxisEnum::LATITUDE->other());
        self::assertSame(AxisEnum::LATITUDE, AxisEnum::LONGITUDE->other());
    }

    public function testLatitudeConventions(): void
    {
        $latitude = AxisEnum::LATITUDE;

        // Latitude describes the north-south position on the globe.
        self::assertSame('lat', $latitude->abbreviation());
        self::assertSame('N', $latitude->positiveCardinal());
        self::assertSame('S', $latitude->negativeCardinal());

        // A latitude cannot be farther than 90 degrees from the equator.
        self::assertSame(90, $latitude->rangeLimit());
    }

    public function testLongitudeConventions(): void
    {
        $longitude = AxisEnum::LONGITUDE;

        // Longitude describes the east-west position around the globe.
        self::assertSame('lon', $longitude->abbreviation());
        self::assertSame('E', $longitude->positiveCardinal());
        self::assertSame('W', $longitude->negativeCardinal());

        // A longitude cannot be farther than 180 degrees from the prime meridian.
        self::assertSame(180, $longitude->rangeLimit());
    }
}
