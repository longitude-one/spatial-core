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

use LongitudeOne\Core\Enum\GeometryTypeEnum;
use LongitudeOne\Core\Enum\TopologicalDimensionEnum;
use PHPUnit\Framework\TestCase;

/**
 * Describes the geometry types used to represent locations, routes, and areas.
 *
 * @internal
 *
 * @coversNothing
 */
final class GeometryTypeEnumTest extends TestCase
{
    /**
     * A location is a point and has no component geometry.
     */
    public function testLocation(): void
    {
        $type = GeometryTypeEnum::POINT;

        self::assertSame(TopologicalDimensionEnum::POINT, $type->topologicalDimension());
        self::assertNull($type->componentType());
        self::assertFalse($type->isCollection());
        self::assertFalse($type->isMulti());
    }

    /**
     * A geometry collection may contain mixed geometry types, so it has no single dimension.
     */
    public function testMixedGeometryCollection(): void
    {
        $type = GeometryTypeEnum::GEOMETRYCOLLECTION;

        self::assertNull($type->topologicalDimension());
        self::assertNull($type->componentType());
        self::assertTrue($type->isCollection());
        self::assertFalse($type->isMulti());
    }

    /**
     * A multipart area remains a surface and consists of areas.
     */
    public function testMultipartArea(): void
    {
        $type = GeometryTypeEnum::MULTIPOLYGON;

        self::assertSame(TopologicalDimensionEnum::SURFACE, $type->topologicalDimension());
        self::assertSame(GeometryTypeEnum::POLYGON, $type->componentType());
        self::assertTrue($type->isMulti());
    }

    /**
     * A multipart route remains a curve and consists of routes.
     */
    public function testMultipartRoute(): void
    {
        $type = GeometryTypeEnum::MULTILINESTRING;

        self::assertSame(TopologicalDimensionEnum::CURVE, $type->topologicalDimension());
        self::assertSame(GeometryTypeEnum::LINESTRING, $type->componentType());
        self::assertTrue($type->isMulti());
    }

    /**
     * A route is a curve made from point locations.
     */
    public function testRoute(): void
    {
        $type = GeometryTypeEnum::LINESTRING;

        self::assertSame(TopologicalDimensionEnum::CURVE, $type->topologicalDimension());
        self::assertSame(GeometryTypeEnum::POINT, $type->componentType());
        self::assertFalse($type->isMulti());
    }
}
