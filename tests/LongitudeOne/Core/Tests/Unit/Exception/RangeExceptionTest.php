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

use LongitudeOne\Core\Enum\AxisEnum;
use LongitudeOne\Core\Exception\RangeException;
use PHPUnit\Framework\Attributes\DataProvider;
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

    /**
     * A long crafted value cannot forge a new log line or inflate the exception message.
     */
    public function testLongValueIsSanitizedBeforeItIsAddedToTheMessage(): void
    {
        $value = str_repeat('a', 99)."\n".str_repeat('b', 10);
        $exception = new RangeException($value, RangeException::LATITUDE_OUT_OF_RANGE);

        $sanitizedValue = str_repeat('a', 99).' ...';

        self::assertSame(
            sprintf('[RangeException] %s, got "%s".', RangeException::LATITUDE_MESSAGE, $sanitizedValue),
            $exception->getMessage(),
        );
        self::assertStringNotContainsString("\n", $exception->getMessage());
        self::assertStringNotContainsString("\r", $exception->getMessage());
    }

    /**
     * Every range error code produces its corresponding message.
     *
     * @param int    $code    range error code
     * @param string $message expected human-readable range message
     */
    #[DataProvider('rangeMessages')]
    public function testRangeMessages(int $code, string $message): void
    {
        $exception = new RangeException('invalid value', $code);

        self::assertSame($code, $exception->getCode());
        self::assertSame(sprintf('[RangeException] %s, got "invalid value".', $message), $exception->getMessage());
    }

    /**
     * @return iterable<string, array{int, string}>
     */
    public static function rangeMessages(): iterable
    {
        yield 'latitude' => [RangeException::LATITUDE_OUT_OF_RANGE, RangeException::LATITUDE_MESSAGE];

        yield 'longitude' => [RangeException::LONGITUDE_OUT_OF_RANGE, RangeException::LONGITUDE_MESSAGE];

        yield 'seconds' => [RangeException::SECONDS_OUT_OF_RANGE, RangeException::SECONDS_MESSAGE];

        yield 'minutes' => [RangeException::MINUTES_OUT_OF_RANGE, RangeException::MINUTES_MESSAGE];

        yield 'unknown code' => [0, RangeException::DEFAULT_MESSAGE];
    }
}
