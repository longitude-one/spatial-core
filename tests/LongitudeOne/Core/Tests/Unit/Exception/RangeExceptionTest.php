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

namespace LongitudeOne\Core\Tests\Unit\Exception;

use LongitudeOne\Core\AxisEnum;
use LongitudeOne\Core\Exception\RangeException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class RangeExceptionTest extends TestCase
{
    /**
     * Tests that latitude values use the latitude range error.
     */
    public function testLatitudeUsesTheLatitudeRangeError(): void
    {
        // The error belongs to the latitude when its value falls outside -90 to 90 degrees.
        $exception = RangeException::forAxis(AxisEnum::LATITUDE, '91');

        self::assertSame(RangeException::LATITUDE_OUT_OF_RANGE, $exception->getCode());
        self::assertSame('[RangeException] Latitude must be between -90 and 90, got "91".', $exception->getMessage());
    }

    /**
     * Tests that longitude values use the longitude range error.
     */
    public function testLongitudeUsesTheLongitudeRangeError(): void
    {
        // The error belongs to the longitude when its value falls outside -180 to 180 degrees.
        $exception = RangeException::forAxis(AxisEnum::LONGITUDE, '181');

        self::assertSame(RangeException::LONGITUDE_OUT_OF_RANGE, $exception->getCode());
        self::assertSame('[RangeException] Longitude must be between -180 and 180, got "181".', $exception->getMessage());
    }
}
