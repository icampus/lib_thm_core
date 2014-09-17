<?php
/**
 * Created by PhpStorm.
 * User: andrej
 * Date: 9/17/14
 * Time: 1:39 PM
 */

class THMStringTest extends  PHPUnit_TestCase
{


    function testContains()
    {
        $this->assetTrue(THMString::contains('abc', 'a'));
        $this->assetTrue(THMString::contains('abc', 'b'));
        $this->assetTrue(THMString::contains('abc', 'c'));
        $this->assetTrue(THMString::contains('abc', 'bc'));
        $this->assetTrue(THMString::contains('abc', 'abc'));

        $this->assetFalse(THMString::contains('abc', 'abcd'));
        $this->assetFalse(THMString::contains('abc', 'f'));
    }
} 