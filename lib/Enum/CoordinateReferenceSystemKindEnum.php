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
 * Kind of coordinate reference system used by a spatial reference system.
 */
enum CoordinateReferenceSystemKindEnum: string
{
    /** A geocentric X, Y, Z coordinate system. */
    case GEOCENTRIC = 'GEOCENTRIC';

    /** A geographic latitude-longitude coordinate system. */
    case GEOGRAPHIC = 'GEOGRAPHIC';

    /** A projected X, Y coordinate system. */
    case PROJECTED = 'PROJECTED';
}
