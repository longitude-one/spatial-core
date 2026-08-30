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
 * Spatial model used to interpret coordinates and spatial calculations.
 */
enum SpatialModelEnum: string
{
    case GEOGRAPHY = 'Geography';
    case GEOMETRY = 'Geometry';

    /**
     * Return whether this is the geography spatial model.
     */
    public function isGeography(): bool
    {
        return self::GEOGRAPHY === $this;
    }

    /**
     * Return whether this is the geometry spatial model.
     */
    public function isGeometry(): bool
    {
        return self::GEOMETRY === $this;
    }

    /**
     * Return whether longitude and latitude values must respect geographic ranges.
     */
    public function requiresGeographicCoordinateRanges(): bool
    {
        return $this->isGeography();
    }
}
