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
 * Byte order used in a Well-Known Binary representation.
 */
enum ByteOrderEnum: int
{
    /** Most-significant octet first; WKB marker 0. */
    case BIG_ENDIAN = 0;

    /** Least-significant octet first; WKB marker 1. */
    case LITTLE_ENDIAN = 1;
}
