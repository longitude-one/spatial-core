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

use LongitudeOne\Core\Enum\CoordinateDimensionEnum;
use PHPUnit\Framework\TestCase;

/**
 * Describes the coordinate layouts used by spatial geometries.
 *
 * @internal
 *
 * @coversNothing
 */
final class CoordinateDimensionEnumTest extends TestCase
{
    /**
     * An elevated location adds altitude to longitude and latitude.
     */
    public function testElevatedLocation(): void
    {
        $location = CoordinateDimensionEnum::XYZ;

        static::assertSame(3, $location->coordinateDimension());
        static::assertTrue($location->is3D());
        static::assertFalse($location->hasM());
        static::assertSame(['X', 'Y', 'Z'], $location->ordinates());
        static::assertNull($location->mIndex());
        static::assertSame(2, $location->zIndex());
        static::assertSame('Z', $location->wktModifier());
    }

    /**
     * An aerial measured route records both altitude and progress along the route.
     */
    public function testElevatedMeasuredRoute(): void
    {
        $route = CoordinateDimensionEnum::XYZM;

        static::assertSame(4, $route->coordinateDimension());
        static::assertTrue($route->is3D());
        static::assertTrue($route->hasM());
        static::assertSame(['X', 'Y', 'Z', 'M'], $route->ordinates());
        static::assertSame(3, $route->mIndex());
        static::assertSame(2, $route->zIndex());
        static::assertSame('ZM', $route->wktModifier());
    }

    /**
     * A measured route records a position along the route without an altitude.
     */
    public function testMeasuredRoute(): void
    {
        $route = CoordinateDimensionEnum::XYM;

        static::assertSame(3, $route->coordinateDimension());
        static::assertFalse($route->is3D());
        static::assertTrue($route->hasM());
        static::assertSame(['X', 'Y', 'M'], $route->ordinates());
        static::assertSame(2, $route->mIndex());
        static::assertNull($route->zIndex());
        static::assertSame('M', $route->wktModifier());
    }

    /**
     * A planar location needs only longitude and latitude.
     */
    public function testPlanarLocation(): void
    {
        $location = CoordinateDimensionEnum::XY;

        static::assertSame(2, $location->coordinateDimension());
        static::assertFalse($location->is3D());
        static::assertFalse($location->hasM());
        static::assertSame(['X', 'Y'], $location->ordinates());
        static::assertNull($location->mIndex());
        static::assertNull($location->zIndex());
        static::assertSame('', $location->wktModifier());
    }
}
