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

namespace LongitudeOne\Core\Exception;

use LongitudeOne\Core\Diagnostic\DiagnosticValueFormatter;
use LongitudeOne\Core\Enum\AxisEnum;

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
     * Creates a range exception for the supplied value and error code.
     *
     * @param string          $value    value that falls outside the permitted range
     * @param int             $code     range error code
     * @param null|\Throwable $previous previous exception, if any
     */
    public function __construct(string $value, int $code, ?\Throwable $previous = null)
    {
        $message = sprintf('[RangeException] %s, got "%s".', $this->setMessage($code), DiagnosticValueFormatter::format($value));

        parent::__construct($message, $code, $previous);
    }

    /**
     * Creates the range exception associated with a geographic axis.
     *
     * @param AxisEnum        $axis     geographic axis whose range was exceeded
     * @param string          $value    value that falls outside the permitted range
     * @param null|\Throwable $previous previous exception, if any
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
     * Returns the range error message associated with an error code.
     *
     * @param int $code range error code
     */
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
