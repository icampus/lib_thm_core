<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.form.formfield');

class JFormFieldCheckAll extends JFormField
{

    protected $type = 'CheckAll';

    public function getInput()
    {
        return JHtml::_('grid.checkall');
    }
}