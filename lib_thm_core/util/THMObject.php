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
    /**
     * Retrieves an object property
     *
     * @param   object  $object    the object being accessed
     * @param   string  $property  the property requested
     * @param   mixed   $default   the default value for the given property
     *
     * @return  mixed  the value of the property if existent, otherwise the default value
     */
    public static function getOr($object, $property, $default = null)
    {
        return property_exists($object, $property) ? $object->$property : $default;
    }
}