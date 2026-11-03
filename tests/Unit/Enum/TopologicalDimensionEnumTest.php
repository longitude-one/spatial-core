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

use LongitudeOne\Core\Enum\TopologicalDimensionEnum;
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

        static::assertTrue($geometry->isSurface());
        static::assertFalse($geometry->isEmpty());
        static::assertFalse($geometry->isPoint());
        static::assertFalse($geometry->isCurve());
    }

    /**
     * An empty geometry does not represent a point, a curve, or a surface.
     */
    public function testEmptyGeometry(): void
    {
        $geometry = TopologicalDimensionEnum::EMPTY;

        static::assertTrue($geometry->isEmpty());
        static::assertFalse($geometry->isPoint());
        static::assertFalse($geometry->isCurve());
        static::assertFalse($geometry->isSurface());
    }

    /**
     * A location is represented by a point.
     */
    public function testLocation(): void
    {
        $geometry = TopologicalDimensionEnum::POINT;

        static::assertTrue($geometry->isPoint());
        static::assertFalse($geometry->isEmpty());
        static::assertFalse($geometry->isCurve());
        static::assertFalse($geometry->isSurface());
    }

    /**
     * A route is represented by a curve.
     */
    public function testRoute(): void
    {
        $geometry = TopologicalDimensionEnum::CURVE;

        static::assertTrue($geometry->isCurve());
        static::assertFalse($geometry->isEmpty());
        static::assertFalse($geometry->isPoint());
        static::assertFalse($geometry->isSurface());
    }

    /**
     * A solid is represented by a volume.
     */
    public function testVolume(): void
    {
        $geometry = TopologicalDimensionEnum::VOLUME;

        static::assertTrue($geometry->isVolume());
        static::assertFalse($geometry->isEmpty());
        static::assertFalse($geometry->isPoint());
        static::assertFalse($geometry->isCurve());
        static::assertFalse($geometry->isSurface());
    }
}
