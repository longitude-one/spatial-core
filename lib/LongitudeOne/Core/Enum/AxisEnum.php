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
 * Geographic coordinate axis and its cardinal constraints.
 *
 *                         N (+latitude)
 *                         |
 *                         |
 * W (-longitude) --------+-------- E (+longitude)
 *                         |
 *                         |
 *                         S (-latitude)
 */
enum AxisEnum
{
    case LATITUDE;
    case LONGITUDE;

    public const EAST_CARDINAL = 'E';
    public const LATITUDE_ABBREVIATION = 'lat';
    public const LATITUDE_RANGE_LIMIT = 90;
    public const LONGITUDE_ABBREVIATION = 'lon';
    public const LONGITUDE_RANGE_LIMIT = 180;
    public const NORTH_CARDINAL = 'N';
    public const SOUTH_CARDINAL = 'S';
    public const WEST_CARDINAL = 'W';

    /**
     * Return the conventional abbreviation for the axis.
     */
    public function abbreviation(): string
    {
        return match ($this) {
            self::LATITUDE => self::LATITUDE_ABBREVIATION,
            self::LONGITUDE => self::LONGITUDE_ABBREVIATION,
        };
    }

    /**
     * Return the cardinal direction associated with negative values on the axis.
     */
    public function negativeCardinal(): string
    {
        return match ($this) {
            self::LATITUDE => self::SOUTH_CARDINAL,
            self::LONGITUDE => self::WEST_CARDINAL,
        };
    }

    /**
     * Return the other axis in a coordinate pair.
     */
    public function other(): self
    {
        return match ($this) {
            self::LATITUDE => self::LONGITUDE,
            self::LONGITUDE => self::LATITUDE,
        };
    }

    /**
     * Return the cardinal direction associated with positive values on the axis.
     */
    public function positiveCardinal(): string
    {
        return match ($this) {
            self::LATITUDE => self::NORTH_CARDINAL,
            self::LONGITUDE => self::EAST_CARDINAL,
        };
    }

    /**
     * Return the maximum absolute degree value for the axis.
     */
    public function rangeLimit(): int
    {
        return match ($this) {
            self::LATITUDE => self::LATITUDE_RANGE_LIMIT,
            self::LONGITUDE => self::LONGITUDE_RANGE_LIMIT,
        };
    }
}
