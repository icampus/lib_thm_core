<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.form.formfield');

class JFormFieldOrderingButton extends JFormField
{

    protected $type = 'OrderingButton';

    public function getInput()
    {
        return JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', 'asc', '', null, 'asc', 'JGRID_HEADING_ORDERING');
    }
}
