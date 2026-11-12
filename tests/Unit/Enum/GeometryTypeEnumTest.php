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

            static::assertSame($name, $type->name);
            static::assertSame($value, $type->value);
            static::assertSame($type, GeometryTypeEnum::tryFrom($value));
            static::assertContains($type, GeometryTypeEnum::cases());
            static::assertSame($dimension, $type->topologicalDimension());
            static::assertSame($component, $type->componentType());
            static::assertTrue($type->isInstantiable());
            static::assertFalse($type->isCollection());
            static::assertFalse($type->isMulti());
        }
    }

    /**
     * A circular route remains a curve made from point locations.
     */
    public function testCircularRoute(): void
    {
        $type = GeometryTypeEnum::CIRCULARSTRING;

        static::assertSame(TopologicalDimensionEnum::CURVE, $type->topologicalDimension());
        static::assertSame(GeometryTypeEnum::POINT, $type->componentType());
        static::assertFalse($type->isMulti());
    }

    /**
     * A route combining line and arc segments does not have one component type.
     */
    public function testCompoundRoute(): void
    {
        $type = GeometryTypeEnum::COMPOUNDCURVE;

        static::assertSame(TopologicalDimensionEnum::CURVE, $type->topologicalDimension());
        static::assertNull($type->componentType());
        static::assertFalse($type->isMulti());
    }

    /**
     * An area bounded by curves remains a surface.
     */
    public function testCurvedArea(): void
    {
        $type = GeometryTypeEnum::CURVEPOLYGON;

        static::assertSame(TopologicalDimensionEnum::SURFACE, $type->topologicalDimension());
        static::assertNull($type->componentType());
        static::assertFalse($type->isMulti());
    }

    /**
     * Generic, abstract, and volumetric types have no homogeneous component.
     */
    public function testGenericAndVolumetricTypes(): void
    {
        $geometry = GeometryTypeEnum::GEOMETRY;
        $curve = GeometryTypeEnum::CURVE;
        $solid = GeometryTypeEnum::SOLID;

        static::assertNull($geometry->componentType());
        static::assertNull($geometry->topologicalDimension());
        static::assertFalse($geometry->isCollection());
        static::assertFalse($geometry->isMulti());
        static::assertFalse($geometry->isInstantiable());

        static::assertNull($curve->componentType());
        static::assertSame(TopologicalDimensionEnum::CURVE, $curve->topologicalDimension());
        static::assertFalse($curve->isCollection());
        static::assertFalse($curve->isMulti());
        static::assertFalse($curve->isInstantiable());

        static::assertNull($solid->componentType());
        static::assertSame(TopologicalDimensionEnum::VOLUME, $solid->topologicalDimension());
        static::assertFalse($solid->isCollection());
        static::assertFalse($solid->isMulti());
        static::assertFalse($solid->isInstantiable());
    }

    /**
     * Generic multipart curves and surfaces retain their respective dimensions.
     */
    public function testGenericMultipartGeometries(): void
    {
        $curves = GeometryTypeEnum::MULTICURVE;
        $surfaces = GeometryTypeEnum::MULTISURFACE;

        static::assertSame(TopologicalDimensionEnum::CURVE, $curves->topologicalDimension());
        static::assertNull($curves->componentType());
        static::assertTrue($curves->isMulti());
        static::assertSame(TopologicalDimensionEnum::SURFACE, $surfaces->topologicalDimension());
        static::assertNull($surfaces->componentType());
        static::assertTrue($surfaces->isMulti());
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

        static::assertCount(\count(GeometryTypeEnum::cases()), $expected);

        foreach (GeometryTypeEnum::cases() as $type) {
            static::assertArrayHasKey($type->name, $expected);
            static::assertSame($expected[$type->name], $type->isInstantiable());
        }
    }

    /**
     * A location is a point and has no component geometry.
     */
    public function testLocation(): void
    {
        $type = GeometryTypeEnum::POINT;

        static::assertSame(TopologicalDimensionEnum::POINT, $type->topologicalDimension());
        static::assertNull($type->componentType());
        static::assertFalse($type->isCollection());
        static::assertFalse($type->isMulti());
    }

    /**
     * A geometry collection may contain mixed geometry types, so it has no single dimension.
     */
    public function testMixedGeometryCollection(): void
    {
        $type = GeometryTypeEnum::GEOMETRYCOLLECTION;

        static::assertNull($type->topologicalDimension());
        static::assertNull($type->componentType());
        static::assertTrue($type->isCollection());
        static::assertFalse($type->isMulti());
    }

    /**
     * A multipart area remains a surface and consists of areas.
     */
    public function testMultipartArea(): void
    {
        $type = GeometryTypeEnum::MULTIPOLYGON;

        static::assertSame(TopologicalDimensionEnum::SURFACE, $type->topologicalDimension());
        static::assertSame(GeometryTypeEnum::POLYGON, $type->componentType());
        static::assertTrue($type->isMulti());
    }

    /**
     * A multipart route remains a curve and consists of routes.
     */
    public function testMultipartRoute(): void
    {
        $type = GeometryTypeEnum::MULTILINESTRING;

        static::assertSame(TopologicalDimensionEnum::CURVE, $type->topologicalDimension());
        static::assertSame(GeometryTypeEnum::LINESTRING, $type->componentType());
        static::assertTrue($type->isMulti());
    }

    /**
     * A route is a curve made from point locations.
     */
    public function testRoute(): void
    {
        $type = GeometryTypeEnum::LINESTRING;

        static::assertSame(TopologicalDimensionEnum::CURVE, $type->topologicalDimension());
        static::assertSame(GeometryTypeEnum::POINT, $type->componentType());
        static::assertFalse($type->isMulti());
    }

    /**
     * A terrain surface is triangulated, while a polyhedral surface is made of polygons.
     */
    public function testTerrainSurfaces(): void
    {
        $tin = GeometryTypeEnum::TIN;
        $polyhedralSurface = GeometryTypeEnum::POLYHEDRALSURFACE;
        $triangle = GeometryTypeEnum::TRIANGLE;

        static::assertSame(TopologicalDimensionEnum::SURFACE, $tin->topologicalDimension());
        static::assertSame(GeometryTypeEnum::TRIANGLE, $tin->componentType());
        static::assertSame(TopologicalDimensionEnum::SURFACE, $polyhedralSurface->topologicalDimension());
        static::assertSame(GeometryTypeEnum::POLYGON, $polyhedralSurface->componentType());
        static::assertSame(TopologicalDimensionEnum::SURFACE, $triangle->topologicalDimension());
        static::assertSame(GeometryTypeEnum::LINESTRING, $triangle->componentType());
    }
}
