<?php
/**
 * @category    Joomla library
 * @package     THM_Core
 * @subpackage  lib_thm_core.site
 * @name        THM_CoreModelList
 * @description Common template for list views
 * @author      James Antrim, <james.antrim@mni.thm.de>
 * @author      Ilja Michajlow, <Ilja.Michajlow@mni.thm.de>
 * @copyright   2014 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.mni.thm.de
 */

/**
 * Class provides standardized output of list items
 *
 * @category    Joomla.Library
 * @package     thm_list
 * @subpackage  lib_thm_list.site
 */
class THM_CoreModelList extends JModelList
{
    protected $defaultOrdering = '';

    protected $defaultDirection = '';

    protected $defaultLimit = '20';

    protected $defaultStart = '0';


    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState($this->context, new stdClass);

        // Pre-fill the list options
        if (!property_exists($data, 'list'))
        {
            // Limit and start are only here to remove errors made by joomla. Pagination does not use these!
            $data->list = array(
                'direction' => $this->state->get('list.direction', $this->defaultDirection),
                'ordering'  => $this->state->get('list.ordering', $this->defaultOrdering),
                'limit'     => $this->state->get('list.limit', $this->defaultLimit),
                'start'     => $this->state->get('list.start', $this->defaultStart)
            );
        }

        return $data;
    }

    /**
     * Overwrites the JModelList populateState function
     *
     * @param   string  $ordering   the column by which the table is should be ordered
     * @param   string  $direction  the direction in which this column should be ordered
     */
    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication();

        // Receive & set filters
        $filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array');
        if (!empty($filters))
        {
            foreach ($filters as $name => $value)
            {
                $this->setState('filter.' . $name, $value);
            }
        }

        $list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array');

        // This is a workaround. The ordering get lost in the state when you use paginagtion. So the ordering is saved
        // to a session variable and read from it if the state ordering is null.
        $session =& JFactory::getSession();
        if(strpos($list['fullordering'], 'null') !== false){
            $list['fullordering'] = $session->get( 'ordering', $list['fullordering'] );
        } else {
            $session->set( 'ordering', $list['fullordering']);
        }

        // This lines may not work correctly, so there is a workaround
        $orderingSet = $this->processFullOrdering($list);
        if($orderingSet){
            $this->setState('list.ordering', $orderingSet[0]);
            $this->setState('list.direction', $orderingSet[1]);
            $this->setState('list.fullordering',  $orderingSet[0] . " " . $orderingSet[1]);
        }
        parent::populateState();
    }

    /**
     * Sets the ordering and direction filters should a valid full ordering request be made
     *
     * @param   array  $list  an array of list variables
     *
     * @return  bool  true if the full ordering exists and is of correct syntax, otherwise false
     */
    private function processFullOrdering($list)
    {
        $defaultOrdering = $this->defaultOrdering;
        $defaultDirection = $this->defaultDirection;
        $defaultFullOrdering = empty($defaultOrdering)? '' : "$defaultOrdering $defaultDirection";

        // Not set
        if (empty($list['fullordering']))
        {
            $this->setState('list.fullordering', $defaultFullOrdering);
            return;
        }

        $orderingParts = explode(' ', $list['fullordering']);

        // Invalid number of arguments
        if (count($orderingParts) != 2)
        {
            $this->setState('list.fullordering', $defaultFullOrdering);
            return;
        }

        // Valid entry
        if (in_array(strtoupper($orderingParts[1]), array('ASC', 'DESC', '')))
        {
            return $orderingParts;
        }

        // Invalid direction
        $this->setState('list.fullordering', $defaultFullOrdering);
        return;
    }

    /**
     * Generates a toggle for the attribute in question
     *
     * @param   int     $id          the id of the database entry
     * @param   bool    $value       the value currently set for the attribute (saves asking it later)
     * @param   string  $controller  the name of the data management controller
     * @param   string  $tip         the tooltip
     * @param   string  $attribute   the resource attribute to be changed (useful if multiple entries can be toggled)
     *
     * @return  string  a HTML string
     */
    protected function getToggle($id, $value, $controller, $tip, $attribute = null)
    {
        $iconClass = empty($value)? 'unpublish' : 'publish';
        $icon = '<i class="icon-' . $iconClass . '"></i>';

        $attributes = array();
        $attributes['title'] = $tip;
        $attributes['class'] = 'btn btn-micro hasTooltip' ;
        $attributes['class'] .= empty($value)? ' inactive' : '';

        $url = 'index.php?option=com_thm_organizer&task=' . $controller . '.toggle&id=' . $id . '&value=' . $value;
        $url .= empty($attribute)? '' : "&attribute=$attribute";
        $link = JHtml::_('link', $url, $icon, $attributes);

        return '<div class="button-grp">' . $link . '</div>';
    }

    /**
     * Provides a default method for setting the list ordering
     *
     * @param   object  &$query  the query object
     *
     * @return  void
     */
    protected function setOrdering(&$query)
    {
        $defaultOrdering = "{$this->defaultOrdering} {$this->defaultDirection}";
        $session =& JFactory::getSession();
        $ordering = $this->state->get('list.fullordering', $defaultOrdering);
        if(strpos($ordering, 'null') !== false){
            $ordering = $session->get( 'ordering', '' );
        }
        $query->order($ordering);
    }
}
