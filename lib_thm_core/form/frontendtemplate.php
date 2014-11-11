<?php
/**
 * @category    Joomla library
 * @package     THM_Core
 * @subpackage  lib_thm_core.site
 * @name        THM_CoreFormTemplateFrontend
 * @description frontend template for itemless forms
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
class THM_CoreTemplateFrontend
{
    /**
     * Method to create a list output
     *
     * @param   object  &$view           the view context calling the function
     *
     * @return void
     */
    public static function render(&$view)
    {
        $option = JFactory::getApplication()->input->get('option');
        $resource = str_replace('_edit', '', $view->get('name'));
        $showHeading = $view->params->get('show_page_heading');
        $title = $view->params->get('page_title');
?>
        <script type="text/javascript">
            Joomla.submitbutton = function(task)
            {
                if (task == '<?php echo $resource; ?>.cancel' || document.formvalidator.isValid(document.id('form-form')))
                {
                    Joomla.submitform(task, document.getElementById('form-form'));
                }
            }
        </script>
        <form action="index.php?option=<?php echo $option; ?>"
              enctype="multipart/form-data"
              method="post"
              name="adminForm"
              id="form-form"
              class="form-horizontal">
            <?php if (!empty($showHeading)): ?>
            <h2 class="componentheading">
                <?php echo $title; ?>
            </h2>
            <?php endif; ?>
            <div class="button-panel">
                <button type="submit" value="submit"><i class="icon-forward-2"></i><?php echo JText::_('JSUBMIT'); ?></button>
            </div>
            <div class="form-horizontal">
                <?php echo $view->form->renderFieldset('details'); ?>
            </div>
            <?php echo $view->form->getInput('id'); ?>
            <?php echo JHtml::_('form.token'); ?>
            <input type="hidden" name="task" value="" />
        </form>
<?php
    }

}
