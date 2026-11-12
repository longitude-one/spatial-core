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
 * Type of measurement unit used by a spatial reference system.
 */
enum UnitTypeEnum: string
{
    /** An angular unit, such as degrees or radians. */
    case ANGULAR = 'ANGULAR';

    /** A linear unit, such as metres. */
    case LINEAR = 'LINEAR';
}
