<?php
/**
 * Created by PhpStorm.
 * User: andrej
 * Date: 9/17/14
 * Time: 1:39 PM
 */

jimport('thm_core.util.THMString');

class THMStringTest extends  TestCase
{
    function testContains()
    {
        $this->assertTrue(THMString::contains('abc', 'a'));
        $this->assertTrue(THMString::contains('abc', 'b'));
        $this->assertTrue(THMString::contains('abc', 'c'));
        $this->assertTrue(THMString::contains('abc', 'bc'));
        $this->assertTrue(THMString::contains('abc', 'abc'));

        $this->assertFalse(THMString::contains('abc', 'abcd'));
        $this->assertFalse(THMString::contains('abc', 'f'));
    }
} 