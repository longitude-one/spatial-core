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
 * Layout of the ordinates in every coordinate of a geometry.
 *
 * This is the coordinate dimension defined by SQL/MM Spatial. It is distinct
 * from a geometry's topological dimension: a point in XYZM still has
 * topological dimension zero.
 */
enum CoordinateDimensionEnum: string
{
    /** A two-dimensional coordinate: X, Y. */
    case XY = 'XY';

    /** A two-dimensional coordinate with a measure: X, Y, M. */
    case XYM = 'XYM';

    /** A three-dimensional coordinate: X, Y, Z. */
    case XYZ = 'XYZ';

    /** A three-dimensional coordinate with a measure: X, Y, Z, M. */
    case XYZM = 'XYZM';

    /**
     * Return the coordinate dimension, namely the number of ordinates in one coordinate.
     */
    public function coordinateDimension(): int
    {
        return match ($this) {
            self::XY => 2,
            self::XYM, self::XYZ => 3,
            self::XYZM => 4,
        };
    }

    /**
     * Return whether the coordinate includes an M ordinate.
     *
     * M is an arbitrary measure, often used for linear referencing; it is not
     * intrinsically a time ordinate.
     */
    public function hasM(): bool
    {
        return match ($this) {
            self::XYM, self::XYZM => true,
            default => false,
        };
    }

    /**
     * Return whether the coordinate includes a Z ordinate.
     *
     * Z commonly represents elevation, but its precise meaning is defined by
     * the coordinate reference system.
     */
    public function hasZ(): bool
    {
        return match ($this) {
            self::XYZ, self::XYZM => true,
            default => false,
        };
    }

    /**
     * Return whether the coordinate has a Z ordinate.
     *
     * This mirrors SQL/MM Spatial's ST_Is3d semantics. XYM is a
     * three-ordinate coordinate, but is not 3D in that sense.
     */
    public function is3d(): bool
    {
        return $this->hasZ();
    }

    /**
     * Return the zero-based M ordinate index, if present.
     */
    public function mIndex(): ?int
    {
        return match ($this) {
            self::XYM => 2,
            self::XYZM => 3,
            default => null,
        };
    }

    /**
     * Return the ordinate names in their canonical order.
     *
     * @return list<'M'|'X'|'Y'|'Z'>
     */
    public function ordinates(): array
    {
        return match ($this) {
            self::XY => ['X', 'Y'],
            self::XYM => ['X', 'Y', 'M'],
            self::XYZ => ['X', 'Y', 'Z'],
            self::XYZM => ['X', 'Y', 'Z', 'M'],
        };
    }

    /**
     * Return the WKT coordinate-dimension modifier.
     *
     * The XY layout has no modifier; the other layouts are written after the
     * geometry type, for example "POINT ZM (...)".
     */
    public function wktModifier(): string
    {
        return match ($this) {
            self::XY => '',
            self::XYM => 'M',
            self::XYZ => 'Z',
            self::XYZM => 'ZM',
        };
    }

    /**
     * Return the zero-based Z ordinate index, if present.
     */
    public function zIndex(): ?int
    {
        return $this->hasZ() ? 2 : null;
    }
}
