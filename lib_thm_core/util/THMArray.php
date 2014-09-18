<?php

class THMArray
{
    /**
     * Transform all elements of on array into a new array.
     *
     * Example: Double all Elements
     * THMArray::map([1,2,3,4], function (elem) { return elem * 2})
     * Will return: [2,4,6,8]
     *
     * @require PHP 5.3.0+
     *
     * @param   array               $array  Array of elements which should be transformed.
     * @param   function (A) -> B   $fn     Transformer function; Transform a element into another.
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
     * @require PHP 5.3.0+
     *
     * @param   array                    $array  Array of elements to filter.
     * @param   function (A) -> Boolean  $fn     Filter function. return true if accept, false otherwise.
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

    public static function mapReduce($array, $mapFn, $reduceFn, $reduceStart)
    {
        $result = self::map($array, $mapFn);
        $fn = self::foldLeft($reduceStart, $reduceFn);
        return $fn($result);
    }

    public static function mapReduceRight($array, $mapFn, $reduceFn, $reduceStart)
    {
        $result = self::map($array, $mapFn);
        $fn = self::foldRight($reduceStart, $reduceFn);
        return $fn($result);
    }

    public static function foldLeft($start, $fn)
    {
        return function ($array) use ($start, $fn)
        {
            $result = $start;
            foreach ($array as $elem) {
                $result = $fn($result, $elem);
            }
            return $result;
        };
    }

    public static function foldRight($start, $fn)
    {
        return function ($array) use ($start, $fn)
        {
            if (empty($array))
                return $start;
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

    public static function getOr($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}
