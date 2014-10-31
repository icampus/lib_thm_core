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
        if (!empty($view->sidebar))
        {
            echo '<div id="j-sidebar-container" class="span2">' . $view->sidebar . '</div>';
        }
        $data = array('view' => $view, 'options' => array());
        $filters = $view->filterForm->getGroup('filter');
?>
        <div id="j-main-container" class="span10">
            <form action="index.php?" id="adminForm"  method="post"
                  name="adminForm" xmlns="http://www.w3.org/1999/html">
                <div class="searchArea">
                    <div class="js-stools clearfix">
                        <div class="clearfix">
                            <div class="js-stools-container-bar">
                                <?php self::renderSearch($filters); ?>
                            </div>
                            <div class="js-stools-container-list hidden-phone hidden-tablet">
                                <?php echo JLayoutHelper::render('joomla.searchtools.default.list', $data); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clr"> </div>
                <table class="table table-striped" id="<?php echo $view->get('name'); ?>-list">
<?php
                    echo '<thead>';
                    self::renderHeader($view->headers);
                    self::renderHeaderFilters($view->headers, $filters);
                    echo '</thead>';
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
     * Renders the search input group if set in the filter xml
     *
     * @param   array  &$filters  the filters set for the view
     *
     * @return  void
     */
    private static function renderSearch(&$filters)
    {
        $showSearch = !empty($filters['filter_search']);
        if (!$showSearch)
        {
            return;
        }
?>
                <label for="filter_search" class="element-invisible">
                    <?php echo JText::_('JSEARCH_FILTER'); ?>
                </label>
                <div class="btn-wrapper input-append">
                    <?php echo $filters['filter_search']->input; ?>
                    <button type="submit" class="btn hasTooltip"
                            title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>">
                        <i class="icon-search"></i>
                    </button>
                </div>
                <div class="btn-wrapper">
                    <button type="button" class="btn hasTooltip js-stools-btn-clear"
                            title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value='';">
                        <i class="icon-delete"></i>
                    </button>
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
        echo '<tr>';
        foreach ($headers as $header)
        {
            echo "<th>$header</th>";
        }
        echo '</tr>';
    }

    /**
     * Renders the table head
     *
     * @param   array  &$headers  an array containing the table headers
     * @param   array  &$filters  the filters set for the view
     *
     * @return  void
     */
    private static function renderHeaderFilters(&$headers, &$filters)
    {
        $noFilters = count($filters) === 0;
        $onlySearch = (count($filters) === 1 AND !empty($filters['filter_search']));
        $dontDisplay = ($noFilters OR $onlySearch);
        if ($dontDisplay)
        {
            return;
        }

        $headerNames = array_keys($headers);
        echo '<tr>';
        foreach ($headerNames as $name)
        {
            $name = str_replace('.', '_', $name);
            $found = false;
            $searchName = "filter_$name";
            foreach ($filters as $fieldName => $field)
            {
                if ($fieldName == $searchName)
                {
                    echo '<th><div class="js-stools-field-filter">';
                    echo $field->input;
                    echo '</div></th>';
                    $found = true;
                    break;
                }
            }
            if ($found)
            {
                continue;
            }
            echo '<th></th>';
        }
        echo '</tr>';
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
