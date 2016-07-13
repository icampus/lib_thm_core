<?php
/**
 * Created by PhpStorm.
 * User: andrej
 * Date: 9/17/14
 * Time: 1:38 PM
 */

jimport('thm_core.util.THMObject');

class THMObjectTest extends TestCase
{
	public function testGetOr()
	{
		$obj             = new stdClass;
		$obj->attribute1 = "test";
		$obj->attribute2 = null;


		$this->assertEquals(THMObject::getOr($obj, 'attribute1', null), 'test');
		$this->assertEquals(THMObject::getOr($obj, 'attribute2', "nil"), null);

		$this->assertEquals(THMObject::getOr($obj, 'attribute5'), null);
		$this->assertEquals(THMObject::getOr($obj, 'attribute5', 5), 5);
	}
} 