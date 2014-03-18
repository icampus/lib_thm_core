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
 * Group that have a valid db state.
 * Can not be changed!
 *
 * @category  Joomla.Library
 * @package   thm_repo.core.state
 */
class THMGroup
{
    private $_id;

    private $_name;

    /**
     * Load a group by id.
     *
     * @param   int  $groupId  The group identifier.
     *
     * @throws InvalidArgumentException if group id not of type int
     * @throws Exception If group can not be loaded.
     * @throws RuntimeException If no group was found using groupId
     */
    public function __construct($groupId)
    {
        if (!is_int($groupId))
        {
            throw new InvalidArgumentException('GroupId is not of type int');
        }

        $this->loadState($groupId);
    }

    /**
     * Return the group identifier.
     *
     * @return int The group identifier
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Return the group name.
     *
     * @return String name The group name.
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Refresh the object state by using the current database state.
     *
     * @return void.
     *
     * @throws Exception If group can not be loaded.
     * @throws RuntimeException If group was deleted by any other application.
     */
    public function refreshState()
    {
        $this->loadState($this->_id);
    }

    /**
     * Load a group state by id.
     *
     * @param   int  $groupId  The group identifier.
     *
     * @return void.
     *
     * @throws Exception If group can not be loaded.
     * @throws RuntimeException If no group was found using groupId
     */
    private function loadState($groupId)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
            ->select('g.id, g.title')
            ->from('#__usergroups g')
            ->where("g.id = {$groupId}");
        $result = $db->setQuery($query)->loadAssoc();


        if (empty($result))
        {
            throw new RuntimeException("Can not load Group with id: $groupId");
        }

        $this->_id = (int) $result['id'];
        $this->_name = $result['title'];
    }
}