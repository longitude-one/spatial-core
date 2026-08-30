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
 * Intrinsic dimension of a geometry's point set.
 *
 * Unlike CoordinateDimensionEnum, this describes the geometry itself, not the
 * number of ordinates used to locate its points. For example, an XYZM point is
 * topologically zero-dimensional and an XY curve is one-dimensional.
 */
enum TopologicalDimensionEnum: int
{
    /** The dimension of a curve geometry. */
    case CURVE = 1;

    /** The dimension of an empty geometry. */
    case EMPTY = -1;

    /** The dimension of a point geometry. */
    case POINT = 0;

    /** The dimension of a surface geometry. */
    case SURFACE = 2;

    /**
     * Return whether this represents a curve geometry.
     */
    public function isCurve(): bool
    {
        return self::CURVE === $this;
    }

    /**
     * Return whether this represents the empty set.
     */
    public function isEmpty(): bool
    {
        return self::EMPTY === $this;
    }

    /**
     * Return whether this represents a point geometry.
     */
    public function isPoint(): bool
    {
        return self::POINT === $this;
    }

    /**
     * Return whether this represents a surface geometry.
     */
    public function isSurface(): bool
    {
        return self::SURFACE === $this;
    }
}
