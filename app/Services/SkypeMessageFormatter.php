<?php

namespace App\Services;

class SkypeMessageFormatter
{
    const SKYPE_NEW_LINE = "\n\n";

    /**
     * @param string $text
     * @return string
     */
    public function bold(string $text): string
    {
        return "**$text**";
    }

    /**
     * Add padding for each row of two-dimensional array.
     * For example:
     * <code>
     * [
     *  ['dev123', 'john_doe'],
     *  ['dev1', 'john_doe'],
     * ]
     *
     * // Result
     * // dev123 john_doe
     * // dev1   john_doe
     * </code>
     *
     * @param string[][] $lines
     * @return string
     */
    public function table(array $lines): string
    {
        if (empty($lines)) {
            return '';
        }

        $maxValues = [];
        foreach ($lines[0] as $column => $value) {
            $length = array_map('strlen', array_column($lines, $column));
            $maxValues[$column] = max($length);
        }

        $result = [];
        foreach ($lines as $line) {
            $padLine = [];
            foreach ($line as $column => $value) {
                $padLine[] = str_pad($value, $maxValues[$column]);
            }

            $result[] = implode(' ', $padLine);
        }

        return implode(self::SKYPE_NEW_LINE, $result);
    }
}
