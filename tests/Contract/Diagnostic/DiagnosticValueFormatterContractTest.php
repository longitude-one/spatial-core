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

namespace LongitudeOne\Core\Tests\Contract\Diagnostic;

use LongitudeOne\Core\Diagnostic\DiagnosticValueFormatter;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class DiagnosticValueFormatterContractTest extends TestCase
{
    private const MAX_EXPECTED_LENGTH = DiagnosticValueFormatter::MAX_LENGTH;

    /**
     * ANSI colour, erase, cursor movement, and reset sequences no longer contain an ESC byte.
     */
    public function testAnsiTerminalSequencesAreNeutralized(): void
    {
        $sequences = [
            "\x1B[31mred\x1B[0m",
            "\x1B[2J",
            "\x1B[2A",
            "\x1B[H",
        ];

        foreach ($sequences as $sequence) {
            $result = DiagnosticValueFormatter::format($sequence);

            self::assertStringNotContainsString("\x1B", $result);
            self::assertStringContainsString('\u{001B}', $result);
            $this->assertDiagnosticInvariants($result);
        }
    }

    /**
     * No ASCII control character, including DEL, is emitted unchanged.
     */
    public function testAsciiControlCharactersAreNeutralized(): void
    {
        $value = implode('', array_map(chr(...), range(0x00, 0x1F))).chr(0x7F);
        $result = DiagnosticValueFormatter::format($value);

        foreach (range(0x00, 0x1F) as $controlCharacter) {
            self::assertStringNotContainsString(chr($controlCharacter), $result);
        }

        self::assertStringNotContainsString(chr(0x7F), $result);
        self::assertStringContainsString('\n', $result);
        self::assertStringContainsString('\r', $result);
        self::assertStringContainsString('\t', $result);
        self::assertStringContainsString('\0', $result);
        $this->assertDiagnosticInvariants($result);
    }

    /**
     * Empty, control-only, whitespace-only, and separator-free inputs remain safe.
     */
    public function testBoundaryInputsAreRobust(): void
    {
        $values = [
            'empty' => '',
            'control only' => "\0\t\n\r\x1B",
            'spaces only' => '     ',
            'very long without separators' => str_repeat('x', self::MAX_EXPECTED_LENGTH * 100),
        ];

        foreach ($values as $name => $value) {
            $result = DiagnosticValueFormatter::format($value);

            if ('empty' === $name) {
                self::assertSame('', $result);
            } else {
                self::assertNotSame('', $result, $name);
            }

            $this->assertDiagnosticInvariants($result);
        }
    }

    /**
     * Formatting an already formatted value cannot change it further.
     */
    public function testFormattingIsIdempotent(): void
    {
        $value = "POINT(1 2)\n\x1B[31m\u{202E}\xC3";
        $result = DiagnosticValueFormatter::format($value);
        $formattedAgain = DiagnosticValueFormatter::format($result);

        self::assertSame($result, $formattedAgain);
        $this->assertDiagnosticInvariants($result);
        $this->assertDiagnosticInvariants($formattedAgain);
    }

    /**
     * Length, controls, ANSI, and invisible Unicode protections compose without weakening one another.
     */
    public function testHostileCombinedInputRespectsEveryProtection(): void
    {
        $value = str_repeat("POINT(1 2)\xC3\n\x1B[2J\u{202E}", self::MAX_EXPECTED_LENGTH);
        $result = DiagnosticValueFormatter::format($value);

        self::assertStringContainsString('POINT(1 2)\xC3\n\u{001B}[2J\u{202E}', $result);
        self::assertStringNotContainsString("\x1B", $result);
        self::assertStringEndsWith('…', $result);
        $this->assertDiagnosticInvariants($result);
    }

    /**
     * Values at and around the limit preserve their content or receive a bounded truncation suffix.
     */
    public function testLengthContract(): void
    {
        $values = [
            'empty' => '',
            'one character' => 'a',
            'one below the limit' => str_repeat('a', self::MAX_EXPECTED_LENGTH - 1),
            'at the limit' => str_repeat('a', self::MAX_EXPECTED_LENGTH),
            'one above the limit' => str_repeat('a', self::MAX_EXPECTED_LENGTH + 1),
            'very large value' => str_repeat('a', self::MAX_EXPECTED_LENGTH * 100),
        ];

        foreach ($values as $name => $value) {
            $result = DiagnosticValueFormatter::format($value);

            if (mb_strlen($value) <= self::MAX_EXPECTED_LENGTH) {
                self::assertSame($value, $result, $name);
            } else {
                self::assertStringEndsWith('…', $result, $name);
            }

            $this->assertDiagnosticInvariants($result);
        }
    }

    /**
     * Truncation neither splits a multibyte character nor invalidates UTF-8.
     */
    public function testMultibyteUtf8TruncationKeepsCharacterBoundaries(): void
    {
        $justBeforeTheLimit = str_repeat('a', self::MAX_EXPECTED_LENGTH - 1).'é';
        $justAfterTheLimit = str_repeat('a', self::MAX_EXPECTED_LENGTH - 2).'東xy京z';

        $preservedResult = DiagnosticValueFormatter::format($justBeforeTheLimit);
        $truncatedResult = DiagnosticValueFormatter::format($justAfterTheLimit);

        self::assertSame($justBeforeTheLimit, $preservedResult);
        self::assertSame(str_repeat('a', self::MAX_EXPECTED_LENGTH - 2).'東…', $truncatedResult);
        $this->assertDiagnosticInvariants($preservedResult);
        $this->assertDiagnosticInvariants($truncatedResult);
    }

    /**
     * Multiple input lines become one safe diagnostic line.
     */
    public function testMultilineInputProducesSingleLine(): void
    {
        $result = DiagnosticValueFormatter::format("first\nsecond\rthird\r\nfourth");

        self::assertSame('first\nsecond\rthird\r\nfourth', $result);
        $this->assertDiagnosticInvariants($result);
    }

    /**
     * Ordinary ASCII, WKT, and common Unicode remain useful and unchanged.
     */
    public function testNormalInputsArePreserved(): void
    {
        $values = [
            'ASCII' => 'ordinary diagnostic value',
            'WKT' => 'POINT(2.3522 48.8566)',
            'common Unicode' => 'é 東京 🗺️',
        ];

        foreach ($values as $value) {
            $result = DiagnosticValueFormatter::format($value);

            self::assertSame($value, $result);
            $this->assertDiagnosticInvariants($result);
        }
    }

    /**
     * Characters belonging to HTML, JSON, XML, and SQL escaping remain outside this formatter's scope.
     */
    public function testOutOfScopeEscapingCharactersArePreserved(): void
    {
        $value = "<>\"'&{}\\";
        $result = DiagnosticValueFormatter::format($value);

        self::assertSame($value, $result);
        $this->assertDiagnosticInvariants($result);
    }

    /**
     * Bidirectional and zero-width characters have an explicit, non-misleading representation.
     */
    public function testProblematicUnicodeCharactersAreExplicitlyRendered(): void
    {
        $value = "a\u{202E}b\u{202A}c\u{2066}d\u{2069}e\u{200B}f\u{FEFF}g";
        $result = DiagnosticValueFormatter::format($value);

        self::assertSame(
            'a\u{202E}b\u{202A}c\u{2066}d\u{2069}e\u{200B}f\u{FEFF}g',
            $result,
        );
        $this->assertDiagnosticInvariants($result);
    }

    /**
     * A truncated WKT value retains the beginning that identifies it to a developer.
     */
    public function testTruncatedWktPreservesUsefulDiagnosticContext(): void
    {
        $value = 'POLYGON(('.str_repeat('2.3522 48.8566, ', self::MAX_EXPECTED_LENGTH).'))';
        $result = DiagnosticValueFormatter::format($value);

        self::assertStringStartsWith('POLYGON((2.3522 48.8566, ', $result);
        self::assertStringEndsWith('…', $result);
        $this->assertDiagnosticInvariants($result);
    }

    /**
     * Checks the invariants every formatted diagnostic value must respect.
     *
     * @param string $result formatted diagnostic value
     */
    private function assertDiagnosticInvariants(string $result): void
    {
        self::assertStringNotContainsString("\n", $result);
        self::assertStringNotContainsString("\r", $result);
        self::assertTrue(mb_check_encoding($result, 'UTF-8'));
        self::assertLessThanOrEqual(self::MAX_EXPECTED_LENGTH, mb_strlen($result));
    }
}
