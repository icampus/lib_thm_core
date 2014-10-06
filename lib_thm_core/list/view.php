<?php
/**
 * @category    Joomla library
 * @package     THM_List
 * @subpackage  lib_thm_list.site
 * @name        THM_List
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
        $view->filterForm = $view->get('FilterForm');
        $view->activeFilters = $view->get('ActiveFilters');
        $view->state = $view->get('State');
        $view->pagination = $view->get('Pagination');
        $view->headers = $view->get('Headers');
        $view->items = $view->get('Items');
        self::addSubmenu($view);
    }

    /**
     * Configure the Linkbar.
     *
     * @param   object  &$view  the view context calling the function
     *
     * @return void
     */
    private static function addSubmenu(&$view)
    {
        $viewName = $view->get('name');
        // No submenu creation while editing a resource
        if (!strpos($viewName, 'manager'))
        {
            return;
        }

        JHtmlSidebar::addEntry(
            JText::_('COM_THM_ORGANIZER_CAT_TITLE'),
            'index.php?option=com_thm_organizer&amp;view=category_manager',
            $viewName == 'category_manager'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_THM_ORGANIZER_CLM_TITLE'),
            'index.php?option=com_thm_organizer&amp;view=color_manager',
            $viewName == 'color_manager'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_THM_ORGANIZER_DEG_TITLE'),
            'index.php?option=com_thm_organizer&amp;view=degree_manager',
            $viewName == 'degree_manager'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_THM_ORGANIZER_PRM_TITLE'),
            'index.php?option=com_thm_organizer&amp;view=program_manager',
            $viewName == 'program_manager'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_THM_ORGANIZER_FLM_TITLE'),
            'index.php?option=com_thm_organizer&amp;view=field_manager',
            $viewName == 'field_manager'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_THM_ORGANIZER_MON_TITLE'),
            'index.php?option=com_thm_organizer&amp;view=monitor_manager',
            $viewName == 'monitor_manager'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_THM_ORGANIZER_RMM_TITLE'),
            'index.php?option=com_thm_organizer&amp;view=room_manager',
            $viewName == 'room_manager'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_THM_ORGANIZER_SCH_TITLE'),
            'index.php?option=com_thm_organizer&amp;view=schedule_manager',
            $viewName == 'schedule_manager'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_THM_ORGANIZER_POM_TITLE'),
            'index.php?option=com_thm_organizer&amp;view=pool_manager',
            $viewName == 'pool_manager'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_THM_ORGANIZER_SUM_TITLE'),
            'index.php?option=com_thm_organizer&amp;view=subject_manager',
            $viewName == 'subject_manager'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_THM_ORGANIZER_TRM_TITLE'),
            'index.php?option=com_thm_organizer&amp;view=teacher_manager',
            $viewName == 'teacher_manager'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_THM_ORGANIZER_USM_TITLE'),
            'index.php?option=com_thm_organizer&amp;view=user_manager',
            $viewName == 'user_manager'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_THM_ORGANIZER_VSM_TITLE'),
            'index.php?option=com_thm_organizer&amp;view=virtual_schedule_manager',
            $viewName == 'virtual_schedule_manager'
        );

        $view->sidebar = JHtmlSidebar::render();
    }
}
