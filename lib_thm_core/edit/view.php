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
class THM_CoreEditView
{
    /**
     * Method to create a list output
     *
     * @param   object  &$view  the view context calling the function
     *
     * @return void
     */
    public static function setUp(&$view)
    {
        JHtml::_('behavior.framework', true);
        JHtml::_('behavior.formvalidation');
        JHtml::_('behavior.tooltip');

        $view->form = $view->get('Form');

        // Allows for view specific toolbar handling
        $view->addToolBar();
    }
}
