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
class THM_CoreTemplateList
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
        JHtml::_('bootstrap.tooltip');
        JHtml::_('behavior.multiselect');
        JHtml::_('formbehavior.chosen', 'select');

        $layoutData = array('view' => $view, 'options' => array('filtersHidden' => false));
        if (!empty($view->sidebar))
        {
            echo '<div id="j-sidebar-container" class="span2">' . $view->sidebar . '</div>';
        }
?>
        <div id="j-main-container" class="span10">
            <form action="index.php?" id="adminForm"  method="post"
                  name="adminForm" xmlns="http://www.w3.org/1999/html">
                <!--  TODO delete joomla default searchtool & learn to comment in html :D -->
                <?php echo JLayoutHelper::render('joomla.searchtools.default', $layoutData); ?>
                <div class="clr"> </div>
                <table class="table table-striped" id="<?php echo $view->get('name'); ?>-list">
<?php
                    self::renderHeader($view->headers);
                    self::renderBody($view->items);
                    self::renderFooter($view);
?>
                </table>
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="option" value="<?php echo JFactory::getApplication()->input->get('option'); ?>" />
                <input type="hidden" name="view" value="<?php echo $view->get('name'); ?>" />
                <?php echo JHtml::_('form.token');?>
            </form>
        </div>
<?php
    }


    /**
     * Renders the table head
     *
     * @param   array  &$headers  an array containing the table headers
     *
     * @return  void
     */
    private static function renderHeader(&$headers)
    {
        echo '<thead><tr>';
        foreach ($headers as $header)
        {
            echo "<th>$header</th>";
        }
        echo '</tr></thead>';
    }

    /**
     * Renders the table head
     *
     * @param   array  &$items  an array containing the table headers
     *
     * @return  void
     */
    private static function renderBody(&$items)
    {
        $rowClass = 'row0';
        echo '<tbody>';
        foreach ($items as $row)
        {
            echo "<tr class='$rowClass'>";
            foreach ($row as $column)
            {
                echo "<td>$column</td>";
            }
            echo '</tr>';
            $rowClass = $rowClass == 'row0'? 'row1' : 'row0';
        }
        echo '</thead>';
    }

    /**
     * Renders the table foot
     *
     * @param   object  &$view  the view context calling the function
     *
     * @return  void
     */
    private static function renderFooter(&$view)
    {
        $columnCount = count($view->headers);
        echo '<tfoot><tr>';
        echo "<td colspan='$columnCount'>";
        echo $view->pagination->getListFooter();
        echo '</td></tr></tfoot>';
    }
}
