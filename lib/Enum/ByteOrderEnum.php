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
 * Byte order used in a Well-Known Binary representation.
 */
enum ByteOrderEnum: int
{
    /** Most-significant octet first; WKB marker 0. */
    case BIG_ENDIAN = 0;

    /** Least-significant octet first; WKB marker 1. */
    case LITTLE_ENDIAN = 1;
}
