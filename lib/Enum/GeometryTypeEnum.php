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

namespace LongitudeOne\Core\Enum;

/**
 * Type of a spatial geometry.
 */
enum GeometryTypeEnum: string
{
    /** A solid bounded by exterior and interior surface shells. */
    case BREPSOLID = 'BRepSolid';

    /** A simple closed circular curve. */
    case CIRCLE = 'Circle';

    /** A route formed from circular arcs. */
    case CIRCULARSTRING = 'CircularString';

    /** A curve with clothoid interpolation. */
    case CLOTHOID = 'Clothoid';

    /** A route made of linear and circular segments. */
    case COMPOUNDCURVE = 'CompoundCurve';

    /** A surface formed from connected surface patches. */
    case COMPOUNDSURFACE = 'CompoundSurface';

    /** An abstract curve geometry type. */
    case CURVE = 'Curve';

    /** An area bounded by curves. */
    case CURVEPOLYGON = 'CurvePolygon';

    /** A curve with elliptical interpolation. */
    case ELLIPTICALCURVE = 'EllipticalCurve';

    /** A route formed from geodesic segments. */
    case GEODESICSTRING = 'GeodesicString';

    /** The generic geometry type. */
    case GEOMETRY = 'Geometry';

    /** A geometry collection. */
    case GEOMETRYCOLLECTION = 'GeometryCollection';

    /** A linear route. */
    case LINESTRING = 'LineString';

    /** A collection of routes. */
    case MULTICURVE = 'MultiCurve';

    /** A collection of linear routes. */
    case MULTILINESTRING = 'MultiLineString';

    /** A collection of locations. */
    case MULTIPOINT = 'MultiPoint';

    /** A collection of areas. */
    case MULTIPOLYGON = 'MultiPolygon';

    /** A collection of surfaces. */
    case MULTISURFACE = 'MultiSurface';

    /** A non-uniform rational B-spline curve. */
    case NURBSCURVE = 'NURBSCurve';

    /** A location. */
    case POINT = 'Point';

    /** An area. */
    case POLYGON = 'Polygon';

    /** A surface composed of polygon patches. */
    case POLYHEDRALSURFACE = 'PolyhedralSurface';

    /** A solid geometry type. */
    case SOLID = 'Solid';

    /** A curve with spiral interpolation. */
    case SPIRALCURVE = 'SpiralCurve';

    /** An abstract surface geometry type. */
    case SURFACE = 'Surface';

    /** A triangulated irregular network. */
    case TIN = 'TIN';

    /** A triangular area. */
    case TRIANGLE = 'Triangle';

    /**
     * Return the homogeneous component type, if the geometry type has one.
     */
    public function componentType(): ?self
    {
        return match ($this) {
            self::CIRCLE, self::CIRCULARSTRING, self::GEODESICSTRING, self::LINESTRING, self::MULTIPOINT => self::POINT,
            self::POLYGON, self::MULTILINESTRING, self::TRIANGLE => self::LINESTRING,
            self::MULTIPOLYGON, self::POLYHEDRALSURFACE => self::POLYGON,
            self::TIN => self::TRIANGLE,
            default => null,
        };
    }

    /**
     * Return whether this is a geometry collection.
     */
    public function isCollection(): bool
    {
        return self::GEOMETRYCOLLECTION === $this;
    }

    /**
     * Return whether this geometry type can be instantiated.
     *
     * GEOMETRY, CURVE, SOLID, and SURFACE are non-instantiable; every other enum case is instantiable.
     */
    public function isInstantiable(): bool
    {
        return match ($this) {
            self::CURVE,
            self::GEOMETRY,
            self::SOLID,
            self::SURFACE => false,
            default => true,
        };
    }

    /**
     * Return whether this is a multi-geometry type.
     */
    public function isMulti(): bool
    {
        return match ($this) {
            self::MULTICURVE,
            self::MULTILINESTRING,
            self::MULTIPOINT,
            self::MULTIPOLYGON,
            self::MULTISURFACE => true,
            default => false,
        };
    }

    /**
     * Return the topological dimension, or null when it is not specific to a geometry type.
     */
    public function topologicalDimension(): ?TopologicalDimensionEnum
    {
        return match ($this) {
            self::POINT, self::MULTIPOINT => TopologicalDimensionEnum::POINT,
            self::CIRCLE,
            self::CIRCULARSTRING,
            self::CLOTHOID,
            self::COMPOUNDCURVE,
            self::CURVE,
            self::ELLIPTICALCURVE,
            self::GEODESICSTRING,
            self::LINESTRING,
            self::MULTICURVE,
            self::MULTILINESTRING,
            self::NURBSCURVE,
            self::SPIRALCURVE => TopologicalDimensionEnum::CURVE,
            self::COMPOUNDSURFACE,
            self::CURVEPOLYGON,
            self::MULTIPOLYGON,
            self::MULTISURFACE,
            self::POLYGON,
            self::POLYHEDRALSURFACE,
            self::SURFACE,
            self::TIN,
            self::TRIANGLE => TopologicalDimensionEnum::SURFACE,
            self::BREPSOLID, self::SOLID => TopologicalDimensionEnum::VOLUME,
            default => null,
        };
    }
}
