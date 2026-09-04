# Spatial core: longitude-one/spatial-core

![longitude-one/spatial-core](https://img.shields.io/badge/longitude--one-spatial--core-blue)
![Stable release](https://img.shields.io/github/v/release/longitude-one/spatial-core)
[![PHP CI](https://github.com/longitude-one/spatial-core/actions/workflows/tests-php.yaml/badge.svg)](https://github.com/longitude-one/spatial-core/actions/workflows/tests-php.yaml)
![Minimum PHP Version](https://img.shields.io/packagist/php-v/longitude-one/spatial-core.svg?maxAge=3600)
[![Downloads](https://img.shields.io/packagist/dm/longitude-one/spatial-core.svg)](https://packagist.org/packages/longitude-one/spatial-core)
[![codecov](https://codecov.io/gh/longitude-one/spatial-core/graph/badge.svg?token=I6F6GZL5KK)](https://codecov.io/gh/longitude-one/spatial-core)

Shared spatial primitives for LongitudeOne spatial libraries.

The package provides spatial models, geometry types, coordinate and topological dimensions, geographic
axis conventions, and exceptions for values outside their valid range.

## Installation

```bash
composer require longitude-one/spatial-core:1.1.0
```

## Support policy

This package supports PHP 8.3 and later. Its public API includes the enums documented below,
`DiagnosticValueFormatter`, `ExceptionInterface`, and `RangeException`.

## Geographic axes

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

`GeometryTypeEnum` names the concrete shape of a spatial value, such as a point, line string, polygon,
or geometry collection. It exposes a type's homogeneous component and topological dimension, making
it useful for creating and validating multipart geometries.

```php
use LongitudeOne\Core\Enum\GeometryTypeEnum;
use LongitudeOne\Core\Enum\TopologicalDimensionEnum;

$type = GeometryTypeEnum::MULTIPOLYGON;

assert($type->isMulti());
assert(GeometryTypeEnum::POLYGON === $type->componentType());
assert(TopologicalDimensionEnum::SURFACE === $type->topologicalDimension());
```

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

## Development

Run the test suite:

```bash
composer test
```

Generate the coverage reports:

```bash
composer test-coverage
```

The coverage outputs are written to `.phpunit.cache/code-coverage/`, including `clover.xml` and an HTML report at `html/index.html`.
