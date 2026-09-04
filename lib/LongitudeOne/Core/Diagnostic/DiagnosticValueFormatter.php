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

namespace LongitudeOne\Core\Diagnostic;

/**
 * Produces safe, single-line representations of untrusted diagnostic values.
 */
final class DiagnosticValueFormatter
{
    /**
     * Maximum number of characters in a formatted value, including the truncation suffix.
     */
    public const MAX_LENGTH = 2048;

    private const TRUNCATION_SUFFIX = '…';

    /**
     * Formats a value for inclusion in a diagnostic message.
     *
     * Control, format, and Unicode line-separator characters are rendered as visible escape sequences.
     * Invalid UTF-8 bytes are escaped individually, so the returned value is always valid UTF-8.
     *
     * @param string $value untrusted value to include in a diagnostic message
     */
    public static function format(string $value): string
    {
        // The empty UTF-8 pattern validates the entire input before it is read character by character.
        if (1 === preg_match('//u', $value)) {
            return self::formatValidUtf8($value);
        }

        return self::formatInvalidUtf8($value);
    }

    /**
     * Appends a replacement when it fits within the diagnostic output limit.
     *
     * @param string $formatted       formatted value under construction
     * @param int    $formattedLength character count of the formatted value
     * @param string $replacement     safe representation to append
     */
    private static function appendReplacement(string &$formatted, int &$formattedLength, string $replacement): bool
    {
        $replacementLength = mb_strlen($replacement);

        if (self::MAX_LENGTH < $formattedLength + $replacementLength) {
            return false;
        }

        $formatted .= $replacement;
        $formattedLength += $replacementLength;

        return true;
    }

    /**
     * Returns a safe rendering for one valid UTF-8 character.
     *
     * @param string $character valid UTF-8 character
     */
    private static function formatCharacter(string $character): string
    {
        return match ($character) {
            "\0" => '\0',
            "\t" => '\t',
            "\n" => '\n',
            "\r" => '\r',
            default => 1 === preg_match('/[\p{Cc}\p{Cf}\p{Zl}\p{Zp}]/u', $character)
                ? sprintf('\u{%04X}', mb_ord($character))
                : $character,
        };
    }

    /**
     * Formats a string that contains malformed UTF-8 while retaining its valid characters.
     *
     * @param string $value malformed UTF-8 value
     */
    private static function formatInvalidUtf8(string $value): string
    {
        $formatted = '';
        $formattedLength = 0;
        $offset = 0;
        $valueLength = mb_strlen($value, '8bit');

        while ($offset < $valueLength) {
            $byte = ord($value[$offset]);
            $characterLength = self::utf8CharacterLength($byte);
            $character = mb_substr($value, $offset, $characterLength, '8bit');

            if ($characterLength === mb_strlen($character, '8bit') && 1 === preg_match('//u', $character)) {
                $replacement = self::formatCharacter($character);
                $offset += $characterLength;
            } else {
                $replacement = sprintf('\x%02X', $byte);
                ++$offset;
            }

            if (!self::appendReplacement($formatted, $formattedLength, $replacement)) {
                return self::truncate($formatted);
            }
        }

        return $formatted;
    }

    /**
     * Formats a valid UTF-8 value without expanding the full input into an intermediate array.
     *
     * @param string $value valid UTF-8 value
     */
    private static function formatValidUtf8(string $value): string
    {
        $formatted = '';
        $formattedLength = 0;
        $offset = 0;
        $valueLength = mb_strlen($value, '8bit');

        while ($offset < $valueLength) {
            $characterLength = self::utf8CharacterLength(ord($value[$offset]));
            $character = mb_substr($value, $offset, $characterLength, '8bit');

            if (!self::appendReplacement($formatted, $formattedLength, self::formatCharacter($character))) {
                return self::truncate($formatted);
            }

            $offset += $characterLength;
        }

        return $formatted;
    }

    /**
     * Truncates a diagnostic representation without exceeding the maximum length.
     *
     * @param string $value formatted value to truncate
     */
    private static function truncate(string $value): string
    {
        return mb_substr($value, 0, self::MAX_LENGTH - mb_strlen(self::TRUNCATION_SUFFIX)).self::TRUNCATION_SUFFIX;
    }

    /**
     * Returns the expected byte length for a UTF-8 character starting with a byte.
     *
     * @param int $byte first byte of a potential UTF-8 character
     */
    private static function utf8CharacterLength(int $byte): int
    {
        return match (true) {
            $byte <= 0x7F => 1,
            $byte >= 0xC2 && $byte <= 0xDF => 2,
            $byte >= 0xE0 && $byte <= 0xEF => 3,
            $byte >= 0xF0 && $byte <= 0xF4 => 4,
            default => 1,
        };
    }
}
