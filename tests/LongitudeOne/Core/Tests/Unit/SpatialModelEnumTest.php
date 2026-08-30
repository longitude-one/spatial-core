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

use LongitudeOne\Core\Enum\SpatialModelEnum;
use PHPUnit\Framework\TestCase;

/**
 * Describes the spatial models used to interpret coordinates.
 *
 * @internal
 *
 * @coversNothing
 */
final class SpatialModelEnumTest extends TestCase
{
    /**
     * A plan of a building uses the geometry model and has no geographic bounds.
     */
    public function testBuildingPlan(): void
    {
        $model = SpatialModelEnum::GEOMETRY;

        self::assertTrue($model->isGeometry());
        self::assertFalse($model->isGeography());
        self::assertFalse($model->requiresGeographicCoordinateRanges());
    }

    /**
     * A position on Earth uses the geography model and validates longitude and latitude bounds.
     */
    public function testPositionOnEarth(): void
    {
        $model = SpatialModelEnum::GEOGRAPHY;

        self::assertTrue($model->isGeography());
        self::assertFalse($model->isGeometry());
        self::assertTrue($model->requiresGeographicCoordinateRanges());
    }
}
