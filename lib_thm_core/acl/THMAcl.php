<?php
/**
 * @category    Joomla library
 * @package     THM_Core
 * @subpackage  lib_thm_core
 * @author      Andrej Sajenko, <Andrej.Sajenko@mni.thm.de>
 * @copyright   2013 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.mni.thm.de
 */

/**
 * Basic access control list.
 *
 * @category  Joomla.Library
 * @package   thm_core.acl
 */
class THMAcl
{
	private $_jsonRule;

	private static $_coreCreate = 'core.create';

	private static $_coreEdit = 'core.edit';

	private static $_coreDelete = 'core.delete';

	/**
	 * Create empty rules or create it from a Joomla Asset Rules JSON String.
	 *
	 * @param   String $jsonRules Asset JSON Rules (Optional)
	 */
	public function __construct($jsonRules = null)
	{
		$jsonRules = trim($jsonRules);
		if ($jsonRules === null || empty($jsonRules))
		{
			$this->_jsonRule = array(
				self::$_coreCreate => array(),
				self::$_coreEdit   => array(),
				self::$_coreDelete => array());
		}
		else
		{
			/**
			 * Bug-Workaround:  array cast from json_decoded object
			 * The key is a string key if array is casted from decoded object.
			 * Key have to be a int for a valid key access.
			 *
			 * http://stackoverflow.com/questions/3527872/php-cast-to-array-and-return-type-array-is-not-the-same
			 * http://bugs.php.net/bug.php?id=45346
			 * http://bugs.php.net/bug.php?id=51635
			 * http://bugs.php.net/bug.php?id=46758
			 *
			 * Have to copy all values and cast the string key to a int key!
			 */

			$arr = (array) json_decode($jsonRules);
			$crt = array();
			if (array_key_exists(self::$_coreCreate, $arr))
			{
				foreach ((array) $arr[self::$_coreCreate] as $key => $value)
				{
					$crt[(int) $key] = $value;
				}
			}

			$edt = array();
			if (array_key_exists(self::$_coreEdit, $arr))
			{
				foreach ((array) $arr[self::$_coreEdit] as $key => $value)
				{
					$edt[(int) $key] = $value;
				}
			}

			$del = array();
			if (array_key_exists(self::$_coreDelete, $arr))
			{
				foreach ((array) $arr[self::$_coreDelete] as $key => $value)
				{
					$del[(int) $key] = $value;
				}
			}

			$this->_jsonRule = array(
				self::$_coreCreate => $crt,
				self::$_coreEdit   => $edt,
				self::$_coreDelete => $del
			);
		}
	}

	/**
	 * Removes all inherit values from Joomla rules array.
	 *
	 * @param   array[][] $rules Joomla rules.
	 *
	 * @return array[][] The cleared array.
	 */
	public static function clear($rules)
	{
		foreach ($rules as $actionKey => $action)
		{
			foreach ($action as $key => $value)
			{
				if (empty($value) && $value !== '0')
				{
					unset($rules[$actionKey][$key]);
				}
			}
		}

		return $rules;
	}

	/**
	 * Set a Permission in this ACL.
	 *
	 * @param   THMRight $right Permission to set. Will override Permission for the same group.
	 *
	 * @throws InvalidArgumentException If argument have the wrong type.
	 *
	 * @return void
	 */
	public function setRight($right)
	{
		if (!($right instanceof THMRight))
		{
			throw new  InvalidArgumentException('$right is not of type Right!');
		}

		$this->_jsonRule[self::$_coreCreate][$right->getGroup()->getId()] = self::getAccessCode($right->getCreatePerm());
		$this->_jsonRule[self::$_coreEdit][$right->getGroup()->getId()]   = self::getAccessCode($right->getEditPerm());
		$this->_jsonRule[self::$_coreDelete][$right->getGroup()->getId()] = self::getAccessCode($right->getDeletePerm());
	}

	/**
	 * Get the configured Permission for a group.
	 *
	 * @param   int $groupId The identifier for a group.
	 *
	 * @post If a group with groupId exists -> will return right even if you not
	 * set the right. Right's with are not set are always inherited.
	 *
	 * @return THMRight The right for group.
	 *
	 * @throws InvalidArgumentException if groupId is not of type int.
	 */
	public function getRight($groupId)
	{
		if (!is_int($groupId))
		{
			throw new InvalidArgumentException('$group id is not of type int.');
		}

		$createPerm = self::permissionValue($groupId, self::$_coreCreate, $this->_jsonRule);
		$editPerm   = self::permissionValue($groupId, self::$_coreEdit, $this->_jsonRule);
		$deletePerm = self::permissionValue($groupId, self::$_coreDelete, $this->_jsonRule);

		return new THMRight(new THMGroup($groupId), $createPerm, $editPerm, $deletePerm);
	}

	/**
	 * Get the value from joomla asset rules and translate it to a permission code.
	 *
	 * @param   int    $groupId  The group id
	 * @param   String $action   The Action to get.
	 * @param   array  $jsonRule The array of joomla asset rules
	 *
	 * @return THMPermission::value
	 */
	private static function permissionValue($groupId, $action, $jsonRule)
	{
		return (array_key_exists($groupId, $jsonRule[$action]))
			? self::getPermValue($jsonRule[$action][$groupId]) : THMPermission::INHERIT;
	}

	/**
	 * Remove a Right by group id.
	 *
	 * @param   int $groupId The group Id.
	 *
	 * @post After remove a right. All permission will be changed
	 * to inherited for the Group you removed.
	 *
	 * @return void
	 */
	public function removeRight($groupId)
	{
		unset($this->_jsonRule[self::$_coreCreate][$groupId]);
		unset($this->_jsonRule[self::$_coreEdit][$groupId]);
		unset($this->_jsonRule[self::$_coreDelete][$groupId]);
	}

	/**
	 * Convert ACL to the Joomla Asset rule as json format String.
	 *
	 * @return String Joomla asset rules json String.
	 */
	public function toJSONRule()
	{
		return json_encode($this->_jsonRule);
	}

	/**
	 * Translate permission code to joomla rule value.
	 *
	 * @param   Perm ::value  $permValue  The permission code.
	 *
	 * @return String|null The translated permission code.
	 */
	private static function getAccessCode($permValue)
	{
		switch ($permValue)
		{
			case THMPermission::GRANT:
			{
				return 1;
			}
			case THMPermission::DENY:
			{
				return 0;
			}
			default:
			{
				return null;
			}
		}
	}

	/**
	 * Translate the joomla rules access code to the Perm constant value.
	 *
	 * @param   String $accessCode The rules access code
	 *
	 * @return THMPermission::value
	 */
	private static function getPermValue($accessCode)
	{
		switch ($accessCode)
		{
			case 1 :
			{
				return THMPermission::GRANT;
			}
			case 0 :
			{
				return THMPermission::DENY;
			}
			default:
			{
				return THMPermission::INHERIT;
			}
		}
	}
}
