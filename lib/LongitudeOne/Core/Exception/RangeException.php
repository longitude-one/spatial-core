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

/**
 * This file is part of the LongitudeOne GeoParser project.
 *
 * PHP 8.3 | 8.4 | 8.5
 *
 * Copyright LongitudeOne - Alexandre Tranchant - Derek J. Lambert.
 * Copyright 2024-2026.
 */

namespace LongitudeOne\Core\Exception;

use LongitudeOne\Core\AxisEnum;

/**
 * RangeException.
 */
class RangeException extends \RangeException implements ExceptionInterface
{
    public const DEFAULT_MESSAGE = 'Unknown range exception';
    public const LATITUDE_MESSAGE = 'Latitude must be between -90 and 90';
    public const LATITUDE_OUT_OF_RANGE = 90;
    public const LONGITUDE_MESSAGE = 'Longitude must be between -180 and 180';
    public const LONGITUDE_OUT_OF_RANGE = 180;
    public const MINUTES_MESSAGE = 'Minutes must be between 0 and 59';
    public const MINUTES_OUT_OF_RANGE = 3600;
    public const SECONDS_MESSAGE = 'Seconds must be between 0 and 59';
    public const SECONDS_OUT_OF_RANGE = 60;

    /**
     * Maximum length of the value embedded in the exception message.
     */
    private const MAX_VALUE_LENGTH = 100;

    public function __construct(string $value, int $code, ?\Throwable $previous = null)
    {
        $message = sprintf('[RangeException] %s, got "%s".', $this->setMessage($code), $this->sanitizeValue($value));

        parent::__construct($message, $code, $previous);
    }

    /**
     * Create the range exception associated with a geographic axis.
     */
    public static function forAxis(AxisEnum $axis, string $value, ?\Throwable $previous = null): self
    {
        return new self(
            $value,
            match ($axis) {
                AxisEnum::LATITUDE => self::LATITUDE_OUT_OF_RANGE,
                AxisEnum::LONGITUDE => self::LONGITUDE_OUT_OF_RANGE,
            },
            $previous,
        );
    }

    /**
     * Strip control characters (CR, LF, ...) and truncate a value before it is embedded in the exception message.
     *
     * Prevents a large or crafted input from inflating the exception message size or forging fake log lines
     * when that message is logged as-is.
     */
    private function sanitizeValue(string $value): string
    {
        $value = (string) preg_replace('/[\x00-\x1F\x7F]/', ' ', $value);

        if (mb_strlen($value) > self::MAX_VALUE_LENGTH) {
            return mb_substr($value, 0, self::MAX_VALUE_LENGTH).'...';
        }

        return $value;
    }

    private function setMessage(int $code): string
    {
        return match ($code) {
            self::LATITUDE_OUT_OF_RANGE => self::LATITUDE_MESSAGE,
            self::LONGITUDE_OUT_OF_RANGE => self::LONGITUDE_MESSAGE,
            self::SECONDS_OUT_OF_RANGE => self::SECONDS_MESSAGE,
            self::MINUTES_OUT_OF_RANGE => self::MINUTES_MESSAGE,
            default => self::DEFAULT_MESSAGE,
        };
    }
}
