<?php
/**
 * @category    Joomla library
 * @package     THM_Core
 * @subpackage  lib_thm_core.site
 * @name        THM_CoreListTemplate
 * @description Common template for list views
 * @author      Melih Cakir, <melih.cakir@mni.thm.de>
 * @author      James Antrim, <james.antrim@mni.thm.de>
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
    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication();

        $search = $app->getUserStateFromRequest($this->context . 'filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $ordering = $app->getUserStateFromRequest($this->context . '.filter_order', 'filter_order');
        $this->setState('list.ordering', $ordering);

        $direction = $app->getUserStateFromRequest($this->context . 'filter.direction', 'filter_direction');
        $this->setState('list.direction', $direction);

        parent::populateState($ordering, $direction);
    }
}
