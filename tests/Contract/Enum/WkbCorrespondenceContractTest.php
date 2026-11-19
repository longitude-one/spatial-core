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

namespace LongitudeOne\Core\Tests\Contract\Enum;

use LongitudeOne\Core\Enum\CoordinateDimensionEnum;
use LongitudeOne\Core\Enum\GeometryTypeEnum;
use PHPUnit\Framework\TestCase;

/**
 * WKB expectations are fixed independently of the enum methods under test.
 *
 * @internal
 *
 * @coversNothing
 */
final class WkbCorrespondenceContractTest extends TestCase
{
    /**
     * SQL/MM WKB uses additive offsets, independently of the enum backing values.
     *
     * @var array<string, int>
     */
    private const DIMENSION_OFFSETS = [
        'XY' => 0,
        'XYZ' => 1000,
        'XYM' => 2000,
        'XYZM' => 3000,
    ];
    /**
     * OGC 06-103r4, section 8, and ISO/IEC CD 13249-3:201x(E), Table 15.
     *
     * @var array<string, int|null>
     */
    private const GEOMETRY_CODES = [
        'BRepSolid' => null,
        'Circle' => null,
        'CircularString' => 8,
        'Clothoid' => null,
        'CompoundCurve' => 9,
        'CompoundSurface' => null,
        'Curve' => null,
        'CurvePolygon' => 10,
        'EllipticalCurve' => null,
        'GeodesicString' => null,
        'Geometry' => null,
        'GeometryCollection' => 7,
        'LineString' => 2,
        'MultiCurve' => 11,
        'MultiLineString' => 5,
        'MultiPoint' => 4,
        'MultiPolygon' => 6,
        'MultiSurface' => 12,
        'NURBSCurve' => null,
        'Point' => 1,
        'Polygon' => 3,
        'PolyhedralSurface' => 15,
        'Solid' => null,
        'SpiralCurve' => null,
        'Surface' => null,
        'TIN' => 16,
        'Triangle' => 17,
    ];

    /**
     * Every layout preserves its identity and has an exact, unique inverse.
     */
    public function testEveryDimensionCorrespondence(): void
    {
        self::assertCount(\count(CoordinateDimensionEnum::cases()), self::DIMENSION_OFFSETS);

        foreach (self::DIMENSION_OFFSETS as $value => $offset) {
            $dimension = CoordinateDimensionEnum::from($value);

            self::assertSame($value, $dimension->value);
            self::assertSame($offset, $dimension->wkbCode());
            self::assertSame($dimension, CoordinateDimensionEnum::tryFromWkbCode($offset));
            self::assertSame($dimension, CoordinateDimensionEnum::tryFromWkbCode($dimension->wkbCode()));
        }

        $actualOffsets = array_map(static fn (CoordinateDimensionEnum $dimension): int => $dimension->wkbCode(), CoordinateDimensionEnum::cases());

        self::assertCount(4, array_unique($actualOffsets));
    }

    /**
     * Every geometry case, including unsupported representations, has an explicit expectation.
     */
    public function testEveryGeometryCorrespondence(): void
    {
        self::assertCount(\count(GeometryTypeEnum::cases()), self::GEOMETRY_CODES);

        foreach (self::GEOMETRY_CODES as $value => $code) {
            $geometry = GeometryTypeEnum::from($value);

            self::assertSame($value, $geometry->value);
            self::assertSame($code, $geometry->wkbCode(), $value);
        }
    }

    /**
     * Only offsets are layouts: geometry codes and other nearby integers are not normalized.
     */
    public function testExactDimensionReverseDomain(): void
    {
        $expected = [
            0 => CoordinateDimensionEnum::XY,
            1000 => CoordinateDimensionEnum::XYZ,
            2000 => CoordinateDimensionEnum::XYM,
            3000 => CoordinateDimensionEnum::XYZM,
        ];

        foreach (range(-1, 3018) as $code) {
            self::assertSame($expected[$code] ?? null, CoordinateDimensionEnum::tryFromWkbCode($code), (string) $code);
        }
    }

    /**
     * Base-code lookup rejects reserved 0/13/14, offsets, and complete dimensioned identifiers.
     */
    public function testExactGeometryReverseDomain(): void
    {
        $expected = [];

        foreach (self::GEOMETRY_CODES as $value => $code) {
            if (null !== $code) {
                $expected[$code] = GeometryTypeEnum::from($value);
            }
        }

        foreach (range(-1, 3018) as $code) {
            self::assertSame($expected[$code] ?? null, GeometryTypeEnum::tryFromWkbCode($code), (string) $code);
        }
    }

    /**
     * Known base codes are unique and round-trip without losing their enum identity.
     */
    public function testGeometryUniquenessAndRoundTrips(): void
    {
        $actualCodes = [];

        foreach (self::GEOMETRY_CODES as $value => $code) {
            if (null === $code) {
                continue;
            }

            $geometry = GeometryTypeEnum::from($value);
            $actualCode = $geometry->wkbCode();

            self::assertSame($geometry, GeometryTypeEnum::tryFromWkbCode($code));
            self::assertNotNull($actualCode);
            self::assertSame($geometry, GeometryTypeEnum::tryFromWkbCode($actualCode));
            $actualCodes[] = $actualCode;
        }

        self::assertCount(15, $actualCodes);
        self::assertSame($actualCodes, array_values(array_unique($actualCodes)));
    }

    /**
     * All 15 represented geometries combine with all four dimensions using addition.
     */
    public function testIdentifiersComposeForEveryRepresentableGeometryAndDimension(): void
    {
        foreach (self::GEOMETRY_CODES as $value => $baseCode) {
            if (null === $baseCode) {
                continue;
            }

            $geometryCode = GeometryTypeEnum::from($value)->wkbCode();
            self::assertNotNull($geometryCode);

            foreach (self::DIMENSION_OFFSETS as $layout => $offset) {
                self::assertSame($baseCode + $offset, $geometryCode + CoordinateDimensionEnum::from($layout)->wkbCode());
            }
        }

        self::assertSame(1001, GeometryTypeEnum::POINT->wkbCode() + CoordinateDimensionEnum::XYZ->wkbCode());
        self::assertNull(GeometryTypeEnum::tryFromWkbCode(1001));
        self::assertNull(CoordinateDimensionEnum::tryFromWkbCode(1001));
    }

    /**
     * Neither method masks EWKB flags, accepts legacy ISO aliases, nor wraps integer boundaries.
     */
    public function testNonCanonicalCodesAreRejected(): void
    {
        $rejected = [
            \PHP_INT_MIN,
            -3000,
            -1000,
            65536,
            1000000,
            1000001,
            1000002,
            1000003,
            1000004,
            1000005,
            0x20000000,
            0x20000001,
            0x40000000,
            0x40000001,
            0x80000000,
            0x80000001,
            0xC0000001,
            0xE0000001,
            0xFFFFFFFF,
            \PHP_INT_MAX,
        ];

        foreach ($rejected as $code) {
            self::assertNull(GeometryTypeEnum::tryFromWkbCode($code));
            self::assertNull(CoordinateDimensionEnum::tryFromWkbCode($code));
        }
    }
}
