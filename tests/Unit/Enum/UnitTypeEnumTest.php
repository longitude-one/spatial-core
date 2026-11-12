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

use LongitudeOne\Core\Enum\UnitTypeEnum;
use PHPUnit\Framework\TestCase;

/**
 * Describes the units used by a spatial reference system.
 *
 * @internal
 *
 * @coversNothing
 */
final class UnitTypeEnumTest extends TestCase
{
    /**
     * A longitude expressed in degrees uses an angular unit.
     */
    public function testAngularUnit(): void
    {
        self::assertSame('ANGULAR', UnitTypeEnum::ANGULAR->value);
    }

    /**
     * A projected distance expressed in metres uses a linear unit.
     */
    public function testLinearUnit(): void
    {
        self::assertSame('LINEAR', UnitTypeEnum::LINEAR->value);
    }
}
