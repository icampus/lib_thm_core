<?php
/**
 * @category    Joomla library
 * @package     THM_Core
 * @subpackage  lib_thm_core.site
 * @name        THM_CoreListView
 * @description Common list view
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
        // Don't know which of these filters does what if anything active had no effect on the active highlighting
        $view->filterForm = $view->get('FilterForm');
        $view->activeFilters = $view->get('ActiveFilters');

        // Items common across list views
        $view->state = $view->get('State');
        $view->pagination = $view->get('Pagination');
        $view->headers = $view->get('Headers');
        $view->items = $view->get('Items');

        // Allows for component specific menu handling
        $option = JFactory::getApplication()->input->get('option', '');
        $path = JPATH_ROOT . "/media/$option/helpers/componenthelper.php";
        require_once($path);
        THM_ComponentHelper::addSubmenu($view);

        // Allows for view specific toolbar handling
        $view->addToolBar();
    }
}
