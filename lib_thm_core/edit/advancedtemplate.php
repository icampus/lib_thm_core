<?php
/**
 * @category    Joomla library
 * @package     THM_Core
 * @subpackage  lib_thm_core.site
 * @name        THM_CoreListTemplate
 * @description Common template for list views
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
class THM_CoreTemplateAdvanced
{
	/**
	 * Method to create a list output
	 *
	 * @param   object &$view the view context calling the function
	 *
	 * @return void
	 */
	public static function render(&$view)
	{
		$option = JFactory::getApplication()->input->get('option');
		?>

		<form action="index.php?option=<?php echo $option; ?>"
		      enctype="multipart/form-data"
		      method="post"
		      name="adminForm"
		      id="item-form"
		      class="form-horizontal">
			<div class="form-horizontal">
				<?php
				echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details'));
				$sets = $view->form->getFieldSets();
				foreach ($sets as $set)
				{
					$isInitialized  = (bool) $view->form->getValue('id');
					$displayInitial = isset($set->displayinitial) ? $set->displayinitial : true;
					if ($displayInitial OR $isInitialized)
					{
						echo JHtml::_('bootstrap.addTab', 'myTab', $set->name, JText::_($set->label, true));
						echo $view->form->renderFieldset($set->name);
						echo JHtml::_('bootstrap.endTab');
					}
				}

				echo JHtml::_('bootstrap.endTabSet');
				?>
			</div>
			<?php echo $view->form->getInput('id'); ?>
			<?php echo JHtml::_('form.token'); ?>
			<input type="hidden" name="task" value=""/>
		</form>
		<?php
	}
}
