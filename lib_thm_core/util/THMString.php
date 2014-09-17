<?php
/**
 * Created by PhpStorm.
 * User: andrej
 * Date: 9/16/14
 * Time: 9:52 PM
 */

class THMString
{
    /**
     * Check if one text contains a other.
     *
     * @param   String  $haystack  The text where to look.
     * @param   String  $needle    The text to look for.
     *
     * @return bool True if $heystack containing $needle, false else.
     */
    public static function contains($haystack, $needle)
    {
        return (strpos($haystack, $needle) !== false);
    }

    public static function containsAny($haystack, $needleArray)
    {
        foreach ($needleArray as $needle)
        {
            if (self::contains($haystack, $needle))
            {
                return true;
            }
        }
        return false;
    }


} 