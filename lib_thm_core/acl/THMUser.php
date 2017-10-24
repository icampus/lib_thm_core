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

/**
 * User that have a valid db state.
 * Can not be changed!
 *
 * @category  Joomla.Library
 * @package   thm_repo.core.state
 */
class THMUser
{
    /**
     * @var JUser
     */
    private $_juser;

    /**
     * Load a user by id.
     *
     * @param   int $userId The user identifier.
     *
     * @throws InvalidArgumentException if user id not of type int
     * @throws Exception If user can not be loaded.
     * @throws RuntimeException If no user was found using userId
     */
    public function __construct($userId)
    {
        if ( ! is_int($userId)) {
            throw new InvalidArgumentException('UserId is not of type int');
        }

        $this->loadState($userId);
    }

    /**
     * User unique identifier.
     *
     * @return int The user unique identifier.
     */
    public function getId()
    {
        return (int)$this->_juser->id;
    }

    /**
     * The users full name.
     *
     * @return String The user full name.
     */
    public function getLogin()
    {
        return $this->_juser->username;
    }

    /**
     * Get the displayed name.
     *
     * @return String Displayed name.
     */
    public function getDisplayName()
    {
        return $this->_juser->name;
    }

    /**
     * Method to check JUser object authorisation against an access control
     * object and optionally an access extension object
     *
     * @param   String $action The name of the action to check for permission.
     * @param   String $assetName The name of the asset on which to perform the action.
     *
     * @return  boolean  True if authorised
     */
    public function authorise($action, $assetName = null)
    {
        return $this->_juser->authorise($action, $assetName);
    }

    /**
     * Refresh the state of this object by using the current database state.
     *
     * @return void.
     *
     * @throws Exception If user can not be loaded.
     * @throws RuntimeException If user was removed by any other application.
     */
    public function refreshState()
    {
        $this->loadState($this->_juser->id);
    }

    /**
     * Load a user state by id.
     *
     * @param   int $userId The user identifier.
     *
     * @return void
     *
     * @throws Exception If user can not be loaded.
     * @throws RuntimeException If no user was found using userId
     */
    private function loadState($userId)
    {
        $user = JFactory::getUser($userId);

        if (empty($user->id)) {
            throw new RuntimeException("Can not load User with id: $userId");
        }

        $this->_juser = $user;
    }
}
