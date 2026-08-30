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
        self::assertSame(0, ByteOrderEnum::BIG_ENDIAN->value);
    }

    /**
     * A little-endian WKB value starts with the marker one.
     */
    public function testLittleEndianMarker(): void
    {
        self::assertSame(1, ByteOrderEnum::LITTLE_ENDIAN->value);
    }
}
