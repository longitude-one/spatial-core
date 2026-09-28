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

        self::assertSame(3, $location->coordinateDimension());
        self::assertTrue($location->is3D());
        self::assertFalse($location->hasM());
        self::assertSame(['X', 'Y', 'Z'], $location->ordinates());
        self::assertNull($location->mIndex());
        self::assertSame(2, $location->zIndex());
        self::assertSame('Z', $location->wktModifier());
    }

    /**
     * An aerial measured route records both altitude and progress along the route.
     */
    public function testElevatedMeasuredRoute(): void
    {
        $route = CoordinateDimensionEnum::XYZM;

        self::assertSame(4, $route->coordinateDimension());
        self::assertTrue($route->is3D());
        self::assertTrue($route->hasM());
        self::assertSame(['X', 'Y', 'Z', 'M'], $route->ordinates());
        self::assertSame(3, $route->mIndex());
        self::assertSame(2, $route->zIndex());
        self::assertSame('ZM', $route->wktModifier());
    }

    /**
     * A measured route records a position along the route without an altitude.
     */
    public function testMeasuredRoute(): void
    {
        $route = CoordinateDimensionEnum::XYM;

        self::assertSame(3, $route->coordinateDimension());
        self::assertFalse($route->is3D());
        self::assertTrue($route->hasM());
        self::assertSame(['X', 'Y', 'M'], $route->ordinates());
        self::assertSame(2, $route->mIndex());
        self::assertNull($route->zIndex());
        self::assertSame('M', $route->wktModifier());
    }

    /**
     * A planar location needs only longitude and latitude.
     */
    public function testPlanarLocation(): void
    {
        $location = CoordinateDimensionEnum::XY;

        self::assertSame(2, $location->coordinateDimension());
        self::assertFalse($location->is3D());
        self::assertFalse($location->hasM());
        self::assertSame(['X', 'Y'], $location->ordinates());
        self::assertNull($location->mIndex());
        self::assertNull($location->zIndex());
        self::assertSame('', $location->wktModifier());
    }
}
