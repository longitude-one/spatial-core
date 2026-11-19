# Spatial Core

[![Release](https://img.shields.io/github/v/release/longitude-one/spatial-core?include_prereleases&sort=semver&label=release)](https://github.com/longitude-one/spatial-core/releases)
[![PHP](https://img.shields.io/packagist/php-v/longitude-one/spatial-core?label=php)](https://packagist.org/packages/longitude-one/spatial-core)
[![License](https://img.shields.io/packagist/l/longitude-one/spatial-core)](https://github.com/longitude-one/spatial-core/blob/main/LICENSE)
[![CI](https://github.com/longitude-one/spatial-core/actions/workflows/ci.yml/badge.svg)](https://github.com/longitude-one/spatial-core/actions/workflows/ci.yml)
[![Coverage](https://codecov.io/gh/longitude-one/spatial-core/branch/main/graph/badge.svg)](https://codecov.io/gh/longitude-one/spatial-core/tree/main)
[![Monthly downloads](https://img.shields.io/packagist/dm/longitude-one/spatial-core)](https://packagist.org/packages/longitude-one/spatial-core)
[![Documentation](https://img.shields.io/badge/docs-v1-blue)](https://www.longitude-one.fr/spatial-core/v1/index.html)

Shared spatial primitives for LongitudeOne PHP libraries.
The package provides spatial models, geometry types, coordinate and topological dimensions,
geographic axis conventions, and diagnostic and range-exception helpers.

## Installation

```bash
composer require longitude-one/spatial-core:^1.3.0
```

## Usage

### Geographic axes

`AxisEnum` describes the two axes of a geographic coordinate:

| Axis      | Abbr. | Positive direction | Negative direction | Valid range         |
|-----------|-------|--------------------|--------------------|---------------------|
| Latitude  | `lat` | North (`N`).       | South (`S`).       | -90 to 90 degrees   |
| Longitude | `lon` | East (`E`)         | West (`W`)         | -180 to 180 degrees |

```php
use LongitudeOne\Core\Enum\AxisEnum;

$latitude = AxisEnum::LATITUDE;

assert('lat' === $latitude->abbreviation());
assert('N' === $latitude->positiveCardinal());
assert('S' === $latitude->negativeCardinal());
assert(90 === $latitude->rangeLimit());

assert(AxisEnum::LONGITUDE === $latitude->other());
```

## Documentation

Read the [official documentation](https://www.longitude-one.fr/spatial-core/v1/index.html).

## Support policy

This package supports PHP 8.3 and later. Its public API includes the enums documented below,
`DiagnosticValueFormatter`, `ExceptionInterface`, and `RangeException`.

## Range exceptions

`RangeException` creates the appropriate exception for an axis. All library exceptions implement `ExceptionInterface`,
allowing callers to handle library failures through one common type.

```php
use LongitudeOne\Core\Enum\AxisEnum;
use LongitudeOne\Core\Exception\ExceptionInterface;
use LongitudeOne\Core\Exception\RangeException;

$axis = AxisEnum::LATITUDE;
$value = 91;

try {
    if (abs($value) > $axis->rangeLimit()) {
        throw RangeException::forAxis($axis, (string) $value);
    }
} catch (ExceptionInterface $exception) {
    // Handle a LongitudeOne spatial-core exception.
}
```

## Enumerations

### `AxisEnum`

`AxisEnum` identifies the geographic axis being handled: latitude or longitude. It centralizes each
axis's abbreviation, cardinal directions, and allowed range, so validation and error messages use the
same convention everywhere.

```php
use LongitudeOne\Core\Enum\AxisEnum;

$axis = AxisEnum::LONGITUDE;

assert('E' === $axis->positiveCardinal());
assert(180 === $axis->rangeLimit());
```

### `CoordinateDimensionEnum`

`CoordinateDimensionEnum` describes the ordinates stored for every position in a geometry. It follows
the SQL/MM Spatial layouts: planar coordinates (`XY`), altitude (`Z`), and/or an arbitrary measure
(`M`), with WKT modifiers `Z`, `M`, and `ZM`. It lets a parser, serializer, or validator handle layouts
such as `XYZM` without scattering special cases; `M` is an arbitrary measure, commonly used for
linear referencing, not inherently time.

```php
use LongitudeOne\Core\Enum\CoordinateDimensionEnum;

$layout = CoordinateDimensionEnum::XYZM;

assert($layout->hasM());
assert($layout->hasZ());
assert(4 === $layout->coordinateDimension());
assert('ZM' === $layout->wktModifier());
```

### `UnitTypeEnum`

`UnitTypeEnum` classifies the unit of measure of a spatial reference system as angular or linear. It
prevents a value in degrees or radians from being treated as a distance in metres.

```php
use LongitudeOne\Core\Enum\UnitTypeEnum;

$unitType = UnitTypeEnum::ANGULAR;

assert('ANGULAR' === $unitType->value);
```

### `CoordinateReferenceSystemKindEnum`

`CoordinateReferenceSystemKindEnum` identifies whether a spatial reference system is geographic,
projected, or geocentric. Unlike `SpatialModelEnum`, it classifies the actual reference system rather
than the model selected for spatial calculations.

```php
use LongitudeOne\Core\Enum\CoordinateReferenceSystemKindEnum;

$kind = CoordinateReferenceSystemKindEnum::PROJECTED;

assert('PROJECTED' === $kind->value);
```

### `TopologicalDimensionEnum`

`TopologicalDimensionEnum` expresses what a geometry represents independently of its coordinate
layout: an empty set (`-1`), point (`0`), curve (`1`), or surface (`2`). It is useful when selecting
operations that apply to routes or areas, regardless of whether their coordinates include altitude or
measures: an `XYZM` point remains topologically zero-dimensional, while an `XY` curve remains
one-dimensional.

```php
use LongitudeOne\Core\Enum\TopologicalDimensionEnum;

$dimension = TopologicalDimensionEnum::SURFACE;

assert($dimension->isSurface());
assert(!$dimension->isCurve());
```

### `GeometryTypeEnum`

`GeometryTypeEnum` identifies geometry types and exposes their homogeneous component and topological
dimension. Its `isInstantiable()` method returns `false` for `GEOMETRY`, `CURVE`, `SOLID`, and `SURFACE`,
and `true` for every other case, including `MULTICURVE` and `MULTISURFACE`.

The additional types `BREPSOLID`, `CIRCLE`, `CLOTHOID`, `COMPOUNDSURFACE`, `ELLIPTICALCURVE`,
`GEODESICSTRING`, `NURBSCURVE`, and `SPIRALCURVE` are instantiable. `BREPSOLID` has volume dimension,
`COMPOUNDSURFACE` has surface dimension, and the remaining types have curve dimension. None is a
multi-geometry or a geometry collection. `CIRCLE` and `GEODESICSTRING` have `POINT` components.
The other additions have no single concrete component type exposed by this enum: compound surfaces
and solid shells can use different surface subtypes, while NURBS curves use weighted control points
and knots, and the other curves use constructive parameters.

These additions follow ISO/IEC DIS 13249-3, fifth-edition ballot text N2593 dated 2015-01-25,
clauses 4.2.7–4.2.12, 4.2.20, 4.2.22, 7.4.1–7.9.1, 8.7.1, and 9.2.1. Enum membership does not imply concrete geometry classes or support for encoding or decoding these types.

```php
use LongitudeOne\Core\Enum\GeometryTypeEnum;
use LongitudeOne\Core\Enum\TopologicalDimensionEnum;

$type = GeometryTypeEnum::MULTIPOLYGON;

assert($type->isMulti());
assert($type->isInstantiable());
assert(GeometryTypeEnum::POLYGON === $type->componentType());
assert(TopologicalDimensionEnum::SURFACE === $type->topologicalDimension());
```

### WKB correspondences

Use the existing geometry and coordinate-dimension enums to compose SQL/MM WKB type identifiers.
Their string backing values remain unchanged.

| Method | Return value |
| --- | --- |
| `GeometryTypeEnum::wkbCode(): ?int` | Canonical geometry base code, or `null` for a type without a correspondence below |
| `GeometryTypeEnum::tryFromWkbCode(int $code): ?self` | Geometry for an exact canonical base code, or `null` |
| `CoordinateDimensionEnum::wkbCode(): int` | Additive dimension offset |
| `CoordinateDimensionEnum::tryFromWkbCode(int $code): ?self` | Layout for an exact dimension offset, or `null` |

The `wkbCode()` methods are instance methods; `tryFromWkbCode()` methods are static.

```php
use LongitudeOne\Core\Enum\CoordinateDimensionEnum;
use LongitudeOne\Core\Enum\GeometryTypeEnum;

$baseCode = GeometryTypeEnum::POINT->wkbCode();
$offset = CoordinateDimensionEnum::XYZ->wkbCode();

assert(1 === $baseCode);
assert(1000 === $offset);
assert(1001 === $baseCode + $offset); // Complete identifier for Point XYZ.
assert(GeometryTypeEnum::POINT === GeometryTypeEnum::tryFromWkbCode(1));
assert(CoordinateDimensionEnum::XYZ === CoordinateDimensionEnum::tryFromWkbCode(1000));
assert(null === GeometryTypeEnum::tryFromWkbCode(1001));
assert(null === CoordinateDimensionEnum::tryFromWkbCode(1001));
```

Both reverse methods perform exact lookups. They do not split a complete identifier into geometry and dimension.
For example, `1000` is a dimension offset, while `1` is a geometry base code; each is rejected by the other enum.
Negative and unknown integers, dimensioned identifiers such as `1001`, and EWKB flags such as `0x80000001` return `null`.
SQL/MM dimension offsets are added, whereas EWKB uses extension-specific bit flags; these methods do not translate EWKB or vendor formats.

| Coordinate layout | Dimension offset |
| --- | ---: |
| `XY` | 0 |
| `XYZ` | 1000 |
| `XYM` | 2000 |
| `XYZM` | 3000 |

Every current geometry case has an explicit correspondence or exclusion:

| Geometry case | Base code | Reference or reason for absence |
| --- | ---: | --- |
| `POINT` | 1 | OGC §8.2.8; ISO Table 15 |
| `LINESTRING` | 2 | OGC §8.2.8; ISO Table 15 |
| `POLYGON` | 3 | OGC §8.2.8; ISO Table 15 |
| `MULTIPOINT` | 4 | OGC §8.2.8; ISO Table 15 |
| `MULTILINESTRING` | 5 | OGC §8.2.8; ISO Table 15 |
| `MULTIPOLYGON` | 6 | OGC §8.2.8; ISO Table 15 |
| `GEOMETRYCOLLECTION` | 7 | OGC §8.2.8; ISO Table 15 |
| `CIRCULARSTRING` | 8 | ISO Table 15 |
| `COMPOUNDCURVE` | 9 | ISO Table 15 |
| `CURVEPOLYGON` | 10 | ISO Table 15 |
| `MULTICURVE` | 11 | ISO Table 15 |
| `MULTISURFACE` | 12 | ISO Table 15 |
| `POLYHEDRALSURFACE` | 15 | OGC §8.2.8; ISO Table 15 |
| `TIN` | 16 | OGC §8.2.8; ISO Table 15 |
| `TRIANGLE` | 17 | OGC §8.2.8; ISO Table 15 |
| `GEOMETRY` | `null` | Abstract type; OGC common code 0 has no autonomous WKB representation here |
| `CURVE` | `null` | Abstract type; OGC common code 13 has no autonomous WKB representation here |
| `SURFACE` | `null` | Abstract type; OGC common code 14 has no autonomous WKB representation here |
| `SOLID` | `null` | No correspondence in the WKB references below |
| `BREPSOLID` | `null` | No correspondence in the WKB references below |
| `CIRCLE` | `null` | No correspondence in the WKB references below |
| `CLOTHOID` | `null` | No correspondence in the WKB references below |
| `COMPOUNDSURFACE` | `null` | No correspondence in the WKB references below |
| `ELLIPTICALCURVE` | `null` | No correspondence in the WKB references below |
| `GEODESICSTRING` | `null` | No correspondence in the WKB references below |
| `NURBSCURVE` | `null` | No correspondence in the WKB references below |
| `SPIRALCURVE` | `null` | No correspondence in the WKB references below |

The WKB references used for these correspondences are:

- [OGC Simple Feature Access 1.2.1 (OGC 06-103r4)](https://www.opengis.net/doc/is/sfa/1.2.1), section 8,
  particularly §8.2.3 (Table 7, common and reserved codes) and §8.2.8 (WKB representations).
- ISO/IEC CD 13249-3:201x(E), Committee Draft dated 2009-01-16, clause 5.1.58,
  Table 15, printed pages 177–179. This source is a Committee Draft, not a final published edition.

OGC's common code list includes reserved types beyond the representations it defines.
This API returns `null` for the abstract types above rather than exposing their reserved codes;
enum membership or instantiability alone does not guarantee a WKB correspondence.
For the curve and multi-geometry codes 8–12, the selected ISO draft also lists historical aliases `1000001`–`1000005`.
This API emits and accepts only the canonical codes 8–12; it rejects those aliases.
The four dimension offsets follow the corresponding Z, M, and ZM identifier families in these references.

### `ByteOrderEnum`

`ByteOrderEnum` provides the standard byte-order marker for a Well-Known Binary (WKB) serializer or
parser. Big endian is encoded as `0`; little endian is encoded as `1`.

```php
use LongitudeOne\Core\Enum\ByteOrderEnum;

$byteOrder = ByteOrderEnum::LITTLE_ENDIAN;

assert(1 === $byteOrder->value);
```

### `SpatialModelEnum`

`SpatialModelEnum` distinguishes a planar `GEOMETRY` from a terrestrial `GEOGRAPHY` value. It tells a
caller whether longitude and latitude must be checked against their geographic ranges before building
or persisting a value.

```php
use LongitudeOne\Core\Enum\SpatialModelEnum;

$model = SpatialModelEnum::GEOGRAPHY;

assert($model->isGeography());
assert($model->requiresGeographicCoordinateRanges());
```

## Diagnostic value formatter

`DiagnosticValueFormatter` produces a safe, single-line and bounded representation of an untrusted
value before it is placed in an exception message, log entry, or terminal output. It makes control and
problematic invisible Unicode characters visible, neutralizes terminal escape sequences, preserves valid
UTF-8 where possible, and limits the result to 2,048 characters. It does not validate spatial data or
escape data for HTML, JSON, XML, or SQL.

```php
use LongitudeOne\Core\Diagnostic\DiagnosticValueFormatter;

$value = "POINT(2.3522 48.8566)\n";

assert('POINT(2.3522 48.8566)\\n' === DiagnosticValueFormatter::format($value));
```

Exception factories can use the formatter while retaining their package-specific exception hierarchy:

```php
use LongitudeOne\Core\Diagnostic\DiagnosticValueFormatter;

final class InvalidValueException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function invalidCoordinate(string $coordinate): self
    {
        return new self(sprintf(
            'Invalid coordinate "%s".',
            DiagnosticValueFormatter::format($coordinate),
        ));
    }
}
```

## Contributing

See [Contributing](CONTRIBUTING.md) for development setup, checks and contribution guidelines.
