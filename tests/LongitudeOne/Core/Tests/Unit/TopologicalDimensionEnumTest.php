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

use LongitudeOne\Core\TopologicalDimensionEnum;
use PHPUnit\Framework\TestCase;

/**
 * Describes the intrinsic dimensions of geometry point sets.
 *
 * @internal
 *
 * @coversNothing
 */
final class TopologicalDimensionEnumTest extends TestCase
{
    /**
     * An area is represented by a surface.
     */
    public function testArea(): void
    {
        $geometry = TopologicalDimensionEnum::SURFACE;

        self::assertTrue($geometry->isSurface());
        self::assertFalse($geometry->isEmpty());
        self::assertFalse($geometry->isPoint());
        self::assertFalse($geometry->isCurve());
    }

    /**
     * An empty geometry does not represent a point, a curve, or a surface.
     */
    public function testEmptyGeometry(): void
    {
        $geometry = TopologicalDimensionEnum::EMPTY;

        self::assertTrue($geometry->isEmpty());
        self::assertFalse($geometry->isPoint());
        self::assertFalse($geometry->isCurve());
        self::assertFalse($geometry->isSurface());
    }

    /**
     * A location is represented by a point.
     */
    public function testLocation(): void
    {
        $geometry = TopologicalDimensionEnum::POINT;

        self::assertTrue($geometry->isPoint());
        self::assertFalse($geometry->isEmpty());
        self::assertFalse($geometry->isCurve());
        self::assertFalse($geometry->isSurface());
    }

    /**
     * A route is represented by a curve.
     */
    public function testRoute(): void
    {
        $geometry = TopologicalDimensionEnum::CURVE;

        self::assertTrue($geometry->isCurve());
        self::assertFalse($geometry->isEmpty());
        self::assertFalse($geometry->isPoint());
        self::assertFalse($geometry->isSurface());
    }
}
