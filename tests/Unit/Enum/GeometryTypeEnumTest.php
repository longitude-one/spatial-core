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
     * Additional ISO geometry types retain their identity and complete classification.
     */
    public function testAdditionalGeometryTypes(): void
    {
        $expected = [
            ['BREPSOLID', 'BRepSolid', TopologicalDimensionEnum::VOLUME, null],
            ['CIRCLE', 'Circle', TopologicalDimensionEnum::CURVE, GeometryTypeEnum::POINT],
            ['CLOTHOID', 'Clothoid', TopologicalDimensionEnum::CURVE, null],
            ['COMPOUNDSURFACE', 'CompoundSurface', TopologicalDimensionEnum::SURFACE, null],
            ['ELLIPTICALCURVE', 'EllipticalCurve', TopologicalDimensionEnum::CURVE, null],
            ['GEODESICSTRING', 'GeodesicString', TopologicalDimensionEnum::CURVE, GeometryTypeEnum::POINT],
            ['NURBSCURVE', 'NURBSCurve', TopologicalDimensionEnum::CURVE, null],
            ['SPIRALCURVE', 'SpiralCurve', TopologicalDimensionEnum::CURVE, null],
        ];

        foreach ($expected as [$name, $value, $dimension, $component]) {
            $type = GeometryTypeEnum::from($value);

            self::assertSame($name, $type->name);
            self::assertSame($value, $type->value);
            self::assertSame($type, GeometryTypeEnum::tryFrom($value));
            self::assertContains($type, GeometryTypeEnum::cases());
            self::assertSame($dimension, $type->topologicalDimension());
            self::assertSame($component, $type->componentType());
            self::assertTrue($type->isInstantiable());
            self::assertFalse($type->isCollection());
            self::assertFalse($type->isMulti());
        }
    }

    /**
     * A circular route remains a curve made from point locations.
     */
    public function testCircularRoute(): void
    {
        $type = GeometryTypeEnum::CIRCULARSTRING;

        self::assertSame(TopologicalDimensionEnum::CURVE, $type->topologicalDimension());
        self::assertSame(GeometryTypeEnum::POINT, $type->componentType());
        self::assertFalse($type->isMulti());
    }

    /**
     * A route combining line and arc segments does not have one component type.
     */
    public function testCompoundRoute(): void
    {
        $type = GeometryTypeEnum::COMPOUNDCURVE;

        self::assertSame(TopologicalDimensionEnum::CURVE, $type->topologicalDimension());
        self::assertNull($type->componentType());
        self::assertFalse($type->isMulti());
    }

    /**
     * An area bounded by curves remains a surface.
     */
    public function testCurvedArea(): void
    {
        $type = GeometryTypeEnum::CURVEPOLYGON;

        self::assertSame(TopologicalDimensionEnum::SURFACE, $type->topologicalDimension());
        self::assertNull($type->componentType());
        self::assertFalse($type->isMulti());
    }

    /**
     * Generic, abstract, and volumetric types have no homogeneous component.
     */
    public function testGenericAndVolumetricTypes(): void
    {
        $geometry = GeometryTypeEnum::GEOMETRY;
        $curve = GeometryTypeEnum::CURVE;
        $solid = GeometryTypeEnum::SOLID;

        self::assertNull($geometry->componentType());
        self::assertNull($geometry->topologicalDimension());
        self::assertFalse($geometry->isCollection());
        self::assertFalse($geometry->isMulti());
        self::assertFalse($geometry->isInstantiable());

        self::assertNull($curve->componentType());
        self::assertSame(TopologicalDimensionEnum::CURVE, $curve->topologicalDimension());
        self::assertFalse($curve->isCollection());
        self::assertFalse($curve->isMulti());
        self::assertFalse($curve->isInstantiable());

        self::assertNull($solid->componentType());
        self::assertSame(TopologicalDimensionEnum::VOLUME, $solid->topologicalDimension());
        self::assertFalse($solid->isCollection());
        self::assertFalse($solid->isMulti());
        self::assertFalse($solid->isInstantiable());
    }

    /**
     * Generic multipart curves and surfaces retain their respective dimensions.
     */
    public function testGenericMultipartGeometries(): void
    {
        $curves = GeometryTypeEnum::MULTICURVE;
        $surfaces = GeometryTypeEnum::MULTISURFACE;

        self::assertSame(TopologicalDimensionEnum::CURVE, $curves->topologicalDimension());
        self::assertNull($curves->componentType());
        self::assertTrue($curves->isMulti());
        self::assertSame(TopologicalDimensionEnum::SURFACE, $surfaces->topologicalDimension());
        self::assertNull($surfaces->componentType());
        self::assertTrue($surfaces->isMulti());
    }

    /**
     * Every geometry type has an explicit instantiability classification.
     */
    public function testInstantiabilityClassification(): void
    {
        $expected = [
            GeometryTypeEnum::SPIRALCURVE->name => true,
            GeometryTypeEnum::NURBSCURVE->name => true,
            GeometryTypeEnum::GEODESICSTRING->name => true,
            GeometryTypeEnum::ELLIPTICALCURVE->name => true,
            GeometryTypeEnum::COMPOUNDSURFACE->name => true,
            GeometryTypeEnum::CLOTHOID->name => true,
            GeometryTypeEnum::CIRCLE->name => true,
            GeometryTypeEnum::BREPSOLID->name => true,
            GeometryTypeEnum::CIRCULARSTRING->name => true,
            GeometryTypeEnum::COMPOUNDCURVE->name => true,
            GeometryTypeEnum::CURVE->name => false,
            GeometryTypeEnum::CURVEPOLYGON->name => true,
            GeometryTypeEnum::GEOMETRY->name => false,
            GeometryTypeEnum::GEOMETRYCOLLECTION->name => true,
            GeometryTypeEnum::LINESTRING->name => true,
            GeometryTypeEnum::MULTICURVE->name => true,
            GeometryTypeEnum::MULTILINESTRING->name => true,
            GeometryTypeEnum::MULTIPOINT->name => true,
            GeometryTypeEnum::MULTIPOLYGON->name => true,
            GeometryTypeEnum::MULTISURFACE->name => true,
            GeometryTypeEnum::POINT->name => true,
            GeometryTypeEnum::POLYGON->name => true,
            GeometryTypeEnum::POLYHEDRALSURFACE->name => true,
            GeometryTypeEnum::SOLID->name => false,
            GeometryTypeEnum::SURFACE->name => false,
            GeometryTypeEnum::TIN->name => true,
            GeometryTypeEnum::TRIANGLE->name => true,
        ];

        self::assertCount(count(GeometryTypeEnum::cases()), $expected);

        foreach (GeometryTypeEnum::cases() as $type) {
            self::assertArrayHasKey($type->name, $expected);
            self::assertSame($expected[$type->name], $type->isInstantiable());
        }
    }

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

    /**
     * A terrain surface is triangulated, while a polyhedral surface is made of polygons.
     */
    public function testTerrainSurfaces(): void
    {
        $tin = GeometryTypeEnum::TIN;
        $polyhedralSurface = GeometryTypeEnum::POLYHEDRALSURFACE;
        $triangle = GeometryTypeEnum::TRIANGLE;

        self::assertSame(TopologicalDimensionEnum::SURFACE, $tin->topologicalDimension());
        self::assertSame(GeometryTypeEnum::TRIANGLE, $tin->componentType());
        self::assertSame(TopologicalDimensionEnum::SURFACE, $polyhedralSurface->topologicalDimension());
        self::assertSame(GeometryTypeEnum::POLYGON, $polyhedralSurface->componentType());
        self::assertSame(TopologicalDimensionEnum::SURFACE, $triangle->topologicalDimension());
        self::assertSame(GeometryTypeEnum::LINESTRING, $triangle->componentType());
    }
}
