<?php
/**
 * @category    Joomla library
 * @package     THM_Core
 * @subpackage  lib_thm_core.site
 * @name        THM_CoreListModel
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
class THM_CoreListModel extends JModelList
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
            $data->list = array(
                'direction' => $this->state->get('list.direction', $this->defaultDirection),
                'limit'     => $this->state->get('list.limit', $this->defaultLimit),
                'ordering'  => $this->state->get('list.ordering', $this->defaultOrdering),
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

        $orderingSet = $this->processFullOrdering();
        if (!$orderingSet)
        {
            $ordering = $app->getUserStateFromRequest($this->context . '.list.order', 'list.order', $this->defaultOrdering);
            $this->state->set('list.ordering', $ordering);

            $direction = $app->getUserStateFromRequest($this->context . 'list.direction', 'list.direction', $this->defaultDirection);
            $this->state->set('list.direction', $direction);
        }


        $limit = $app->getUserStateFromRequest($this->context . 'list.limit', 'list.limit', $this->defaultLimit);
        $this->state->set('list.limit', $limit);

        $start = $app->getUserStateFromRequest($this->context . 'list.start', 'list.start', $this->defaultStart);
        $this->state->set('list.start', $start);
    }

    /**
     * Sets the ordering and direction filters should a valid full ordering request be made
     *
     * @return  bool  true if the full ordering exists and is of correct syntax, otherwise false
     */
    private function processFullOrdering()
    {
        $fullOrdering = JFactory::getApplication()->getUserStateFromRequest($this->context . '.list.fullordering', 'list.fullordering', '');

        // Not set
        if (empty($fullOrdering))
        {
            return false;
        }

        $orderingParts = explode($fullOrdering, ' ');

        // Invalid number of arguments
        if (count($orderingParts) != 2)
        {
            return false;
        }

        // Valid entry
        if (in_array(strtoupper($fullOrdering[1]), array('ASC', 'DESC', '')))
        {
            $this->setState('list.ordering', $fullOrdering[0]);
            $this->setState('list.direction', $fullOrdering[1]);
            return true;
        }

        // Invalid direction
        return false;
    }
}
