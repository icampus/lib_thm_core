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
 * Util class to handle Objects.
 *
 * @category  Joomla.Library
 * @package   thm_core.util
 */
class THMObject
{
    public static function getOr($object, $property, $default = null)
    {
        return property_exists($object, $property) ? $object->$property : $default;
    }
}