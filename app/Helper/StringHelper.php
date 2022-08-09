<?php


namespace App\Helper;


class StringHelper
{
    /**
     * Character constants.
     */
    protected const CHARACTER_DOT = '.';
    protected const CHARACTER_SPACE = ' ';
    protected const CHARACTER_PERCENTAGE = '%';

    /**
     * @param int $index
     * @param string $option
     * @return string
     */
    public static function formatMenuOption(int $index, string $option): string
    {
        return $index . self::CHARACTER_DOT . self::CHARACTER_SPACE . $option;
    }

    /**
     * @param string $text
     * @param float $percentage
     * @return string
     */
    public static function formatMenuFooter(string $text, float $percentage): string
    {
        return self::formatNumberPercentage($percentage) . self::CHARACTER_SPACE . $text;
    }

    public static function formatNumberPercentage(float $percentage): string
    {
        return $percentage . self::CHARACTER_PERCENTAGE;
    }
}
