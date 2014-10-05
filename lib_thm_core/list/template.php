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
class THM_List
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
?>
    <form action="index.php?" id="adminForm"  method="post"
          name="adminForm" xmlns="http://www.w3.org/1999/html">
<?php
if ($showSearch or !empty($view->filters))
{
    self::renderFilterBar($view, $showSearch);
}
?>
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
        <input type="hidden" name="option" value="<?php echo $view->model->get('option'); ?>" />
        <input type="hidden" name="view" value="<?php echo $view->get('name'); ?>" />
        <?php echo JHtml::_('form.token');?>
    </form>
<?php
    }

    /**
     * Renders the table head
     *
     * @param   object  &$view       the view context calling the function
     * @param   bool    $showSearch  whether the search box should be shown
     *
     * @return  void
     */
    private static function renderFilterBar(&$view, $showSearch)
    {
        echo '<div class="filter-bar">';
        if ($showSearch)
        {
?>
            <div class="filter-search fltlft pull-left">
                <label class="filter-search-lbl" for="filter_search">
                <?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
                </label>
                <input type="text" name="filter_search" id="filter_search"
                       value="<?php echo $view->escape($view->state->get('filter.search')); ?>"
                       title="<?php echo JText::_('COM_THM_ORGANIZER_SEARCH_TITLE'); ?>" />
                <button type="submit" class="btn hasTooltip">
                    <?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>
                </button>
                <button type="button" class="btn hasTooltip js-stools-btn-clear"
                        onclick="document.id('filter_search').value='';this.form.submit();"
                        title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>">
                <?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
                </button>
            </div>
<?php
        }
        if (count($view->filters))
        {
            foreach ($view->filters as $filter)
            {
                echo '<div class="filter-select fltrt pull-right">' . $filter . '</div>';
            }
        }
        echo '</div>';
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
