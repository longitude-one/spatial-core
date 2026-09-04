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

namespace LongitudeOne\Core\Tests\Unit\Diagnostic;

use LongitudeOne\Core\Diagnostic\DiagnosticValueFormatter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class DiagnosticValueFormatterTest extends TestCase
{
    /**
     * Makes control characters and terminal escapes visible without creating a new terminal line.
     */
    public function testItEscapesControlCharactersAndTerminalSequences(): void
    {
        $value = "POINT(1 2)\r\nforged\0\t\x1B[31mred\x1B[0m";

        self::assertSame(
            'POINT(1 2)\r\nforged\0\t\u{001B}[31mred\u{001B}[0m',
            DiagnosticValueFormatter::format($value),
        );
    }

    /**
     * Retains valid UTF-8 around malformed bytes and renders those bytes visibly.
     */
    public function testItEscapesMalformedUtf8Bytes(): void
    {
        $value = "Paris € \xC3(";

        self::assertSame('Paris € \xC3(', DiagnosticValueFormatter::format($value));
    }

    /**
     * Makes invisible Unicode formatting and line-separator characters visible.
     */
    public function testItEscapesProblematicInvisibleUnicodeCharacters(): void
    {
        $value = "POLYGON\u{202E}(0 0)\u{2028}hidden\u{200B}";

        self::assertSame(
            'POLYGON\u{202E}(0 0)\u{2028}hidden\u{200B}',
            DiagnosticValueFormatter::format($value),
        );
    }

    /**
     * Leaves ordinary, valid UTF-8 values unchanged.
     */
    public function testItPreservesAnOrdinaryValue(): void
    {
        self::assertSame('POINT(2.3522 48.8566)', DiagnosticValueFormatter::format('POINT(2.3522 48.8566)'));
    }

    /**
     * Keeps the formatted output bounded, including when escaped characters expand its length.
     *
     * @param string $value value that exceeds the formatted output limit
     */
    #[DataProvider('oversizedValues')]
    public function testItTruncatesOversizedValues(string $value): void
    {
        $formatted = DiagnosticValueFormatter::format($value);

        self::assertSame(DiagnosticValueFormatter::MAX_LENGTH, mb_strlen($formatted));
        self::assertStringEndsWith('…', $formatted);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function oversizedValues(): iterable
    {
        yield 'plain characters' => [str_repeat('a', DiagnosticValueFormatter::MAX_LENGTH + 1)];

        yield 'expanding newlines' => [str_repeat("\n", DiagnosticValueFormatter::MAX_LENGTH)];

        yield 'malformed UTF-8 bytes' => [str_repeat("\xFF", DiagnosticValueFormatter::MAX_LENGTH)];
    }
}
