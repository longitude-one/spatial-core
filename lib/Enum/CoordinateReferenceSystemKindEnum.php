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
