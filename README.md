# Spatial core: longitude-one/spatial-core

![longitude-one/spatial-core](https://img.shields.io/badge/longitude--one-spatial--core-blue)
![Stable release](https://img.shields.io/github/v/release/longitude-one/spatial-core)
[![PHP CI](https://github.com/longitude-one/spatial-core/actions/workflows/tests-php.yaml/badge.svg)](https://github.com/longitude-one/spatial-core/actions/workflows/tests-php.yaml)
![Minimum PHP Version](https://img.shields.io/packagist/php-v/longitude-one/spatial-core.svg?maxAge=3600)
[![Downloads](https://img.shields.io/packagist/dm/longitude-one/spatial-core.svg)](https://packagist.org/packages/longitude-one/spatial-core)

Shared geographic primitives for LongitudeOne spatial libraries.

The package currently provides the conventions for geographic coordinate axes and the exceptions used when a value falls outside its valid range.

## Installation

```bash
composer require longitude-one/spatial-core:0.0.0
```

## Geographic axes

`AxisEnum` describes the two axes of a geographic coordinate:

| Axis      | Abbr. | Positive direction | Negative direction | Valid range         |
|-----------|-------|--------------------|--------------------|---------------------|
| Latitude  | `lat` | North (`N`).       | South (`S`).       | -90 to 90 degrees   |
| Longitude | `lon` | East (`E`)         | West (`W`)         | -180 to 180 degrees |

```php
use LongitudeOne\Core\AxisEnum;

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
use LongitudeOne\Core\AxisEnum;
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

## Support policy

This package supports PHP 8.3 and later. Its public API currently consists of `AxisEnum`, `ExceptionInterface`, and `RangeException`.
