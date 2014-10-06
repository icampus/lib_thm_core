<?php
/**
 * @category    Joomla library
 * @package     THM_List
 * @subpackage  lib_thm_core.site
 * @name        THM_Core
 * @description Sets standardized list attributes
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
class THM_CoreListView
{
    /**
     * Method to create a list output
     *
     * @param   object  &$view  the view context calling the function
     *
     * @return void
     */
    public static function display(&$view)
    {
        // Filters which are hidden until search tools is clicked
        $view->filterForm = $view->get('FilterForm');

        // Filters which are always shown
        $view->activeFilters = $view->get('ActiveFilters');


        $view->state = $view->get('State');
        $view->pagination = $view->get('Pagination');
        $view->headers = $view->get('Headers');
        $view->items = $view->get('Items');
    }

}
