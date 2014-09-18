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
 * Util class to handle and transform arrays.
 *
 * @category  Joomla.Library
 * @package   thm_core.util
 */
class THMArray
{
    /**
     * Transform all elements of on array into a new array.
     *
     * Example: Double all Elements
     * THMArray::map([1,2,3,4], function (elem) { return elem * 2})
     * Will return: [2,4,6,8]
     *
     * @param   array          $array  Array of elements which should be transformed.
     * @param   function(A):B  $fn     Transformer function; Transform a element into another.
     *
     * @require PHP 5.3.0+
     *
     * @return  array  The array with the transformed elements.
     */
    public static function map($array, $fn)
    {
        $result = array();
        foreach ($array as $elem)
        {
            $result[] = $fn($elem);
        }
        return $result;
    }

    /**
     * Filter all elements of on array.
     *
     * Example: Filter to get all even numbers.
     * HArray::filter([1,2,3,4], function ($elem) {return $elem % 2 == 0;})
     * Will return: [2,4]
     *
     * Example: Filter to get all number greater 10.
     * HArray::filter([1,2,3,4], function ($elem) {return $elem > 10;})
     * Will return: [] empty array.
     *
     * @param   array                $array  Array of elements to filter.
     * @param   function(A):Boolean  $fn     Filter function. return true if accept, false otherwise.
     *
     * @require PHP 5.3.0+
     *
     * @return array  Array containing all accepted element's.
     */
    public static function filter($array, $fn)
    {
        $result = array();
        foreach ($array as $elem)
        {
            if ($fn($elem))
            {
                $result[] = $elem;
            }
        }
        return $result;
    }

    /**
     * Transform all elements of an array and then reduce them to one.
     * Reduce from left to right.
     *
     * @param   array                $array        The array to transform and reduce
     * @param   callable(a):mixed    $mapFn        The map function
     * @param   callable(a,b):mixed  $reduceFn     The reduce function
     * @param   mixed                $reduceStart  The reduce start value
     *
     * @return mixed The reduced value.
     */
    public static function mapReduce($array, $mapFn, $reduceFn, $reduceStart)
    {
        $result = self::map($array, $mapFn);
        $fn = self::foldLeft($reduceStart, $reduceFn);
        return $fn($result);
    }

    /**
     * Transform all elements of an array and then reduce them to one.
     * Reduce from right to left.
     *
     * @param   array                $array        The array to transform and reduce
     * @param   callable(a):mixed    $mapFn        The map function
     * @param   callable(a,b):mixed  $reduceFn     The reduce function
     * @param   mixed                $reduceStart  The reduce start value
     *
     * @return mixed The reduced value.
     */
    public static function mapReduceRight($array, $mapFn, $reduceFn, $reduceStart)
    {
        $result = self::map($array, $mapFn);
        $fn = self::foldRight($reduceStart, $reduceFn);
        return $fn($result);
    }

    /**
     * Create a function which can fold/reduce a array of elements to one element.
     * Reduce from left to right.
     *
     * Usage:
     *  $sumFn = THMArray::foldLeft(0, function ($a, $b) {
     *     return $a + $b;
     *  });
     *  $sumFn(array(1, 2, 3, 4)) -> 10
     *
     * @param   mixed                $start  The start value to reduce a array.
     * @param   callable(a,b):mixed  $fn     The reduce function.
     *
     * @return callable(array) The function to reduce a array.
     */
    public static function foldLeft($start, $fn)
    {
        return function ($array) use ($start, $fn)
        {
            $result = $start;
            foreach ($array as $elem)
            {
                $result = $fn($result, $elem);
            }
            return $result;
        };
    }

    /**
     * Create a function which can fold/reduce a array of elements to one element.
     * Reduce from right to left.
     *
     * Usage:
     *  $sumFn = THMArray::foldRight(0, function ($a, $b) {
     *     return $a + $b;
     *  });
     *  $sumFn(array(1, 2, 3, 4)) -> 10
     *
     * @param   mixed                $start  The start value to reduce a array.
     * @param   callable(a,b):mixed  $fn     The reduce function.
     *
     * @return callable(array) The function to reduce a array.
     */
    public static function foldRight($start, $fn)
    {
        return function ($array) use ($start, $fn)
        {
            if (empty($array))
            {
                return $start;
            }
            else
            {
                $result = $array[count($array) - 1];
                for ($i = count($array) - 2; $i >= 0; $i--)
                {
                    $result = $fn($result, $array[$i]);
                }
                return $fn($result, $start);
            }
        };
    }

    /**
     * Get the value of the array by key if this key exists in the array, or the default value.
     *
     * Usage:
     *  THMArray::getOr(array(1 => 2, 3 => 4), 3, 0) -> 4
     *  THMArray::getOr(array(1 => 2, 3 => 4), 8, 0) -> 0
     *  THMArray::getOr(array(1 => 2, 3 => 4), 8) -> null
     *
     * @param   array  $array    The array to look for the key.
     * @param   mixed  $key      The key of the array.
     * @param   mixed  $default  The default value to return if key not found.
     *
     * @return mixed The found value of the given key or the default value.
     */
    public static function getOr($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}
