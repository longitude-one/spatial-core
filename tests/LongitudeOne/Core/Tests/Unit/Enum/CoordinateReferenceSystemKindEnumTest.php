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

use LongitudeOne\Core\Enum\CoordinateReferenceSystemKindEnum;
use PHPUnit\Framework\TestCase;

/**
 * Describes the coordinate reference systems available to spatial values.
 *
 * @internal
 *
 * @coversNothing
 */
final class CoordinateReferenceSystemKindEnumTest extends TestCase
{
    /**
     * An Earth-centred model uses a geocentric coordinate reference system.
     */
    public function testGeocentricCoordinateReferenceSystem(): void
    {
        self::assertSame('GEOCENTRIC', CoordinateReferenceSystemKindEnum::GEOCENTRIC->value);
    }

    /**
     * A latitude-longitude map uses a geographic coordinate reference system.
     */
    public function testGeographicCoordinateReferenceSystem(): void
    {
        self::assertSame('GEOGRAPHIC', CoordinateReferenceSystemKindEnum::GEOGRAPHIC->value);
    }

    /**
     * A local map in metres uses a projected coordinate reference system.
     */
    public function testProjectedCoordinateReferenceSystem(): void
    {
        self::assertSame('PROJECTED', CoordinateReferenceSystemKindEnum::PROJECTED->value);
    }
}
