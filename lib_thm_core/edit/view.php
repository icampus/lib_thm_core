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
 * Class provides standardized output of an item
 *
 * @category    Joomla.Library
 * @package     thm_list
 * @subpackage  lib_thm_list.site
 */
abstract class THM_CoreViewEdit extends JViewLegacy
{
    public $item = null;

    public $form = null;

    /**
     * Method to get display
     *
     * @param   Object  $tpl  template  (default: null)
     *
     * @return  void
     */
    public function display($tpl = null)
    {
        JHtml::_('bootstrap.tooltip');
        JHtml::_('behavior.framework', true);
        JHtml::_('behavior.formvalidation');
        JHtml::_('formbehavior.chosen', 'select');

        $option = JFactory::getApplication()->input->get('option');
        $document = Jfactory::getDocument();
        $document -> addStyleSheet($this->baseurl . "../../libraries/thm_core/fonts/iconfont.css");
        $document -> addStyleSheet($this->baseurl . "../../media/$option/css/backend.css");
        $document -> addScript($this->baseurl . "../../libraries/thm_core/js/formbehaviorChosenHelper.js");
        $document -> addScript($this->baseurl . "../../libraries/thm_core/js/validators.js");
        $document -> addScript($this->getScript());

        $this->item = $this->get('Item');
        $this->form = $this->get('Form');

        // Allows for view specific toolbar handling
        $this->addToolBar();
        parent::display($tpl);
    }

    /**
     * Concrete classes are supposed to use this method to add a toolbar.
     */
    protected abstract function addToolBar();
    
    /**
     * Method to get the script that have to be included on the form
     *
     * @return string	Script file
     */
    protected function getScript()
    {
        return $this->baseurl . "../../libraries/thm_core/js/submitButton.js";
    }
}
