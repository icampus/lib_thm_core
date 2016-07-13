<?php
/**
 * Created by PhpStorm.
 * User: andrej
 * Date: 9/17/14
 * Time: 1:38 PM
 */

jimport('thm_core.util.THMArray');

class THMArrayTest extends TestCase
{
	public function testMapReduce()
	{
		$arr1 = array(1, 2, 3, 4);

		$result = THMArray::mapReduce($arr1, function ($elem)
		{
			return $elem * 2;
		}, function ($a, $b)
		{
			return $a + $b;
		}, 0);

		$this->assertEquals($result, 20);
		$this->assertEquals($arr1, array(1, 2, 3, 4));
	}

	public function testFold()
	{
		$arr1 = array(1, 2, 3, 4);

		$sumFn = THMArray::foldLeft(0, function ($a, $b)
		{
			return $a + $b;
		});

		$this->assertEquals($sumFn($arr1), 10);
		$this->assertEquals($arr1, array(1, 2, 3, 4));

		$sumFn2 = THMArray::foldLeft(1, function ($a, $b)
		{
			return $a + $b;
		});

		$this->assertEquals($sumFn2($arr1), 11);

		$sumFn3 = THMArray::foldLeft(4, function ($a, $b)
		{
			return $a + $b;
		});

		$this->assertEquals($sumFn3(array()), 4);
	}

	public function testFilter()
	{
		$arr1 = array(1, 2, 3, 4);
		$arr2 = THMArray::filter($arr1, function ($elem)
		{
			return $elem > 2;
		});

		$this->assertEquals($arr2, array(3, 4));
		$this->assertEquals($arr1, array(1, 2, 3, 4));
	}

	public function testMap()
	{
		$arr1 = array(1, 2, 3, 4);

		$arr2 = THMArray::map($arr1, function ($elem)
		{
			return $elem * 2;
		});

		$this->assertEquals($arr2, array(2, 4, 6, 8));
		$this->assertEquals($arr1, array(1, 2, 3, 4));
	}

	public function testGetOr()
	{
		$arr = array(
			'key1' => 'val1',
			'key2' => 'val2',
			4      => 34
		);

		$this->assertEquals(THMArray::getOr($arr, 'key1'), 'val1');
		$this->assertEquals(THMArray::getOr($arr, 'key2', 'T'), 'val2');
		$this->assertEquals(THMArray::getOr($arr, 4), 34);

		$this->assertEquals(THMArray::getOr($arr, 12), null);
		$this->assertEquals(THMArray::getOr($arr, 12, 66), 66);

		$this->assertEquals(THMArray::getOr(array(), 12, 66), 66);
	}
} 