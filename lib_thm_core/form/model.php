<?php
/**
 * @category    Joomla library
 * @package     THM_Core
 * @subpackage  lib_thm_core.site
 * @name        THM_CoreModelForm
 * @author      James Antrim, <james.antrim@mni.thm.de>
 * @copyright   2014 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.mni.thm.de
 */
defined('_JEXEC') or die;

/**
 * Class loads form data to edit an entry.
 *
 * @category    Joomla.Component.Admin
 * @package     thm_core
 * @subpackage  lib_thm_core.site
 */
class THM_CoreModelForm extends JModelForm
{
    /**
     * Method to get the form
     *
     * @param   Array    $data      Data         (default: Array)
     * @param   Boolean  $loadData  Load data  (default: true)
     *
     * @return  mixed  JForm object on success, False on error.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getForm($data = array(), $loadData = false)
    {
        $option = $this->get('option');

        $path = JPATH_ROOT . "/media/$option/helpers/componentHelper.php";
        $helper = str_replace('com_', '', $option) . 'HelperComponent';
        require_once $path;
        $helper::addActions($this);
        $allowEdit = $helper::allowEdit($this);
        if (!$allowEdit)
        {
            throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 404);
        }

        $name = $this->get('name');
        $form = $this->loadForm("$option.$name", $name, array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form))
        {
            return false;
        }

        return $form;
    }
}
