<?php
/**
 * Created by PhpStorm.
 * User: andrej
 * Date: 9/16/14
 * Time: 9:44 PM
 */

class THMObject
{
    public static function getOr($object, $property, $default = null)
    {
        return property_exists($object, $property) ? $object->$property : $default;
    }
}