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

namespace LongitudeOne\Core\Tests\Unit\Enum;

use LongitudeOne\Core\Enum\ByteOrderEnum;
use PHPUnit\Framework\TestCase;

/**
 * Describes the byte order markers used in Well-Known Binary.
 *
 * @internal
 *
 * @coversNothing
 */
final class ByteOrderEnumTest extends TestCase
{
    /**
     * A big-endian WKB value starts with the marker zero.
     */
    public function testBigEndianMarker(): void
    {
        static::assertSame(0, ByteOrderEnum::BIG_ENDIAN->value);
    }

    /**
     * A little-endian WKB value starts with the marker one.
     */
    public function testLittleEndianMarker(): void
    {
        static::assertSame(1, ByteOrderEnum::LITTLE_ENDIAN->value);
    }
}
