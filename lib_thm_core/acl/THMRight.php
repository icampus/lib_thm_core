<?php
/**
 * @category    Joomla library
 * @package     THM_Repo
 * @subpackage  lib_thm_repo
 * @author      Andrej Sajenko, <Andrej.Sajenko@mni.thm.de>
 * @copyright   2013 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.mni.thm.de
 */

// TODO: Redesign for a real common library usage.

/**
 * Rights for a Group.
 * Actions:
 *  create
 *  delete
 *  edit
 *
 * @category  Joomla.Library
 * @package   thm_repo-core
 */
class THMRight
{
	private $_group;

	private $_create, $_edit, $_delete;

	/**
	 * Create a Right.
	 *
	 * @param   THMGroup $group        The Group that should have the permission's
	 * @param            THMPermission ::value  $create  The Create Permission
	 * @param            THMPermission ::value  $edit    The Edit Permission
	 * @param            THMPermission ::value  $delete  The Delete Permission
	 *
	 * @throws InvalidArgumentException If argument's have a wrong type.
	 */
	public function __construct($group, $create, $edit, $delete)
	{
		if (!($group instanceof THMGroup)
			|| !THMPermission::isValidPermission($create)
			|| !THMPermission::isValidPermission($edit)
			|| !THMPermission::isValidPermission($delete)
		)
		{
			throw new  InvalidArgumentException('One or more argument/s have a wrong type');
		}

		$this->_group  = $group;
		$this->_create = $create;
		$this->_edit   = $edit;
		$this->_delete = $delete;
	}

	/**
	 * Return the Group.
	 *
	 * @return THMGroup The group object.
	 */
	public function getGroup()
	{
		return $this->_group;
	}

	/**
	 * Set the create permission.
	 *
	 * @param   THMPermission ::value  $permValue  The Permission value.
	 *
	 * @throws InvalidArgumentException If is not a valid permission.
	 *
	 * @return void
	 */
	public function setCreatePerm($permValue)
	{
		if (!THMPermission::isValidPermission($permValue))
		{
			throw new InvalidArgumentException('Invalid Permission value!');
		}

		$this->_create = $permValue;
	}

	/**
	 * Set the edit permission.
	 *
	 * @param   THMPermission ::value  $permValue  The Permission value.
	 *
	 * @throws InvalidArgumentException If is not a valid permission.
	 *
	 * @return void
	 */
	public function setEditPerm($permValue)
	{
		if (!THMPermission::isValidPermission($permValue))
		{
			throw new InvalidArgumentException('Invalid Permission value!');
		}

		$this->_edit = $permValue;
	}

	/**
	 * Set the delete permission.
	 *
	 * @param   THMPermission ::value  $permValue  The Permission value.
	 *
	 * @throws InvalidArgumentException If is not a valid permission.
	 *
	 * @return void
	 */
	public function setDeletePerm($permValue)
	{
		if (!THMPermission::isValidPermission($permValue))
		{
			throw new InvalidArgumentException('Invalid Permission value!');
		}

		$this->_delete = $permValue;
	}

	/**
	 * The create permission.
	 *
	 * @return THMPermission::value
	 */
	public function getCreatePerm()
	{
		return $this->_create;
	}

	/**
	 * The edit permission.
	 *
	 * @return THMPermission::value
	 */
	public function getEditPerm()
	{
		return $this->_edit;
	}

	/**
	 * The delete permission.
	 *
	 * @return THMPermission::value
	 */
	public function getDeletePerm()
	{
		return $this->_delete;
	}
}
