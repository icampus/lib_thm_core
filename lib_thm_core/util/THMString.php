<?php
/**
 * @category    Joomla library
 * @package     THM_Core
 * @subpackage  lib_thm_core
 * @author      Andrej Sajenko, <Andrej.Sajenko@mni.thm.de>
 * @copyright   2014 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.mni.thm.de
 */

/**
 * Util class to handle and transform Strings.
 *
 * @category  Joomla.Library
 * @package   thm_core.util
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

    /**
     * Check if one text contains a other one which stored in an array.
     *
     * @param   String  $haystack     The text where to look.
     * @param   array   $needleArray  The text to look for.
     *
     * @return bool True if $heystack containing any $needle, false else.
     * Will return true if one $needle was found.
     */
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
