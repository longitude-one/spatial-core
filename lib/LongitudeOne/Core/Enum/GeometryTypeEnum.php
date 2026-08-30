<?php
/**
 * This file is part of the spatial project.
 *
 * PHP 8.4 | 8.5
 *
 * Copyright Alexandre Tranchant <alexandre.tranchant@gmail.com> 2024-2026
 * Copyright Longitude One 2024-2026
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace LongitudeOne\Core\Enum;

use LongitudeOne\Core\TopologicalDimensionEnum;

/**
 * Type of a spatial geometry.
 */
enum GeometryTypeEnum: string
{
    /** A geometry collection. */
    case GEOMETRYCOLLECTION = 'GeometryCollection';

    /** A linear route. */
    case LINESTRING = 'LineString';

    /** A collection of linear routes. */
    case MULTILINESTRING = 'MultiLineString';

    /** A collection of locations. */
    case MULTIPOINT = 'MultiPoint';

    /** A collection of areas. */
    case MULTIPOLYGON = 'MultiPolygon';

    /** A location. */
    case POINT = 'Point';

    /** An area. */
    case POLYGON = 'Polygon';

    /**
     * Return the homogeneous component type, if the geometry type has one.
     */
    public function componentType(): ?self
    {
        return match ($this) {
            self::LINESTRING, self::MULTIPOINT => self::POINT,
            self::POLYGON, self::MULTILINESTRING => self::LINESTRING,
            self::MULTIPOLYGON => self::POLYGON,
            self::GEOMETRYCOLLECTION, self::POINT => null,
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
     * Return whether this is a multi-geometry type.
     */
    public function isMulti(): bool
    {
        return match ($this) {
            self::MULTILINESTRING, self::MULTIPOINT, self::MULTIPOLYGON => true,
            default => false,
        };
    }

    /**
     * Return the topological dimension, or null for a heterogeneous collection.
     */
    public function topologicalDimension(): ?TopologicalDimensionEnum
    {
        return match ($this) {
            self::POINT, self::MULTIPOINT => TopologicalDimensionEnum::POINT,
            self::LINESTRING, self::MULTILINESTRING => TopologicalDimensionEnum::CURVE,
            self::POLYGON, self::MULTIPOLYGON => TopologicalDimensionEnum::SURFACE,
            self::GEOMETRYCOLLECTION => null,
        };
    }
}
