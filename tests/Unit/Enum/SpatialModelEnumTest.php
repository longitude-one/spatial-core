<?php
/*
 * This file is part of the LongitudeOne Spatial project.
 *
 * Copyright (c) 2026 Alexandre Tranchant <alexandre.tranchant@gmail.com>
 *
 * See LICENSE for copyright and license information.
 *
 */

declare(strict_types=1);

namespace LongitudeOne\Core\Tests\Unit\Enum;

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

        static::assertTrue($model->isGeometry());
        static::assertFalse($model->isGeography());
        static::assertFalse($model->requiresGeographicCoordinateRanges());
    }

    /**
     * A position on Earth uses the geography model and validates longitude and latitude bounds.
     */
    public function testPositionOnEarth(): void
    {
        $model = SpatialModelEnum::GEOGRAPHY;

        static::assertTrue($model->isGeography());
        static::assertFalse($model->isGeometry());
        static::assertTrue($model->requiresGeographicCoordinateRanges());
    }
}
