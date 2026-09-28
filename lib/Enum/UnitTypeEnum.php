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
 * Type of measurement unit used by a spatial reference system.
 */
enum UnitTypeEnum: string
{
    /** An angular unit, such as degrees or radians. */
    case ANGULAR = 'ANGULAR';

    /** A linear unit, such as metres. */
    case LINEAR = 'LINEAR';
}
