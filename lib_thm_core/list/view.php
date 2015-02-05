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
abstract class THM_CoreViewList extends JViewLegacy
{
    public $state = null;

    public $items = null;

    public $pagination = null;

    public $filterForm = null;

    public $activeFilters = null;

    public $headers = null;

    /**
     * Method to create a list output
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return void
     */
    public function display($tpl = null)
    {
        $option = JFactory::getApplication()->input->get('option');
        $document = Jfactory::getDocument();
        $document -> addStyleSheet($this->baseurl . "../../libraries/thm_core/fonts/iconfont.css");
        $document -> addStyleSheet($this->baseurl . "../../media/$option/css/backend.css");
        $document -> addScript($this->getScript());

        JHtml::_('bootstrap.tooltip');
        JHtml::_('behavior.multiselect');
        JHtml::_('formbehavior.chosen', 'select');
        JHtml::_('searchtools.form', '#adminForm', array());

        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Don't know which of these filters does what if anything active had no effect on the active highlighting
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        // Items common across list views
        $this->headers = $this->get('Headers');

        $this->items = $this->get('Items');

        // Allows for component specific menu handling
        $option = JFactory::getApplication()->input->get('option', '');
        $path = JPATH_ROOT . "/media/$option/helpers/componentHelper.php";
        $helper = str_replace('com_', '', $option) . 'HelperComponent';
        require_once $path;
        $helper::addSubmenu($this);

        // Allows for view specific toolbar handling
        $this->addToolBar();
        parent::display();
    }

    /**
     * Concrete classes are supposed to use this method to add a toolbar.
     *
     * @return  void  sets context variables
     */
    protected abstract function addToolBar();

    /**
     * Method to get the script that have to be included on the form
     *
     * @return string	Script file
     */
    protected function getScript()
    {
        // Use Joomla.submitbutton in core.js
        return "";
    }
}
