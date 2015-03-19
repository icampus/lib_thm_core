<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.form.formfield');

/**
 * Class JFormFieldCheckAll
 */
class JFormFieldCheckAll extends JFormField
{
    protected $type = 'CheckAll';

    /**
     * @return mixed
     */
    public function getInput()
    {
        return JHtml::_('grid.checkall');
    }
}