<?php
/**
 * @category    Joomla library
 * @package     THM_Core
 * @subpackage  lib_thm_core.site
 * @name        THM_CoreListView
 * @description Common list view
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
class THM_CoreViewList extends JViewLegacy
{
    /**
     * Method to create a list output
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return void
     */
    public function display($tpl = null)
    {
        JHtml::_('bootstrap.tooltip');
        JHtml::_('behavior.multiselect');
        JHtml::_('formbehavior.chosen', 'select');
        JHtml::_('searchtools.form', '#adminForm', array());

        // Don't know which of these filters does what if anything active had no effect on the active highlighting
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        // Items common across list views
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');
        $this->headers = $this->get('Headers');
        $this->items = $this->get('Items');

        $this->ordering = $this->state->get('list.ordering');
        $this->direction = $this->state->get('list.direction');
        $this->search = $this->state->get('filter.search');

        // Allows for component specific menu handling
        $option = JFactory::getApplication()->input->get('option', '');
        $path = JPATH_ROOT . "/media/$option/helpers/componenthelper.php";
        require_once($path);
        THM_ComponentHelper::addSubmenu($this);

        // Allows for view specific toolbar handling
        $this->addToolBar();
        parent::display();
    }
}
