<?php
/**
 * @category    Joomla library
 * @package     THM_List
 * @subpackage  lib_thm_list.site
 * @name        THM_List
 * @description Common list view
 * @author      Melih Cakir, <melih.cakir@mni.thm.de>
 * @author      James Antrim, <james.antrim@mni.thm.de>
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
class THM_CoreListTemplate
{
    /**
     * Method to create a list output
     *
     * @param   object  &$view           the view context calling the function
     * @param   bool    $showSearch      whether the search box should be shown
     * @param   bool    $showPagination  whether the pagination should be displayed
     *
     * @return void
     */
    public static function render(&$view, $showSearch = true, $showPagination = true)
    {
        if (!empty($view->sidebar))
        {
            echo '<div id="j-sidebar-container" class="span2">' . $view->sidebar . '</div>';
        }
?>
        <div id="j-main-container" class="span10">
            <form action="index.php?" id="adminForm"  method="post"
                  name="adminForm" xmlns="http://www.w3.org/1999/html">
                <?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $view)); ?>
                <div class="clr"> </div>
                <table class="table table-striped" id="<?php echo $view->get('name'); ?>-list">
                    <?php self::renderHeader($view->headers); ?>
                    <?php self::renderBody($view->items); ?>
<?php
            if ($showPagination)
            {
                self::renderFooter($view);
            }
?>
                </table>
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="filter_order" value="<?php echo $view->state->get('list.ordering'); ?>" />
                <input type="hidden" name="filter_order_Dir" value="<?php echo $view->state->get('list.direction'); ?>" />
                <input type="hidden" name="option" value="<?php echo $view->get('option'); ?>" />
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
