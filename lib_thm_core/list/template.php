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
     * @param   object &$view the view context calling the function
     *
     * @return void
     */
    public static function render(&$view)
    {
        if ( ! empty($view->sidebar)) {
            echo '<div id="j-sidebar-container" class="span2">' . $view->sidebar . '</div>';
        }

        $data = array('view' => $view, 'options' => array());
        $filters = $view->filterForm->getGroup('filter');
        ?>
        <div id="j-main-container" class="span10">
            <form action="index.php?" id="adminForm" method="post"
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
                <div class="clr"></div>
                <table class="table table-striped" id="<?php echo $view->get('name'); ?>-list">
                    <?php
                    echo '<thead>';
                    self::renderHeader($view->headers);
                    self::renderHeaderFilters($view->headers, $filters);
                    echo '</thead>';
                    self::renderBody($view->items);
                    self::renderFooter($view);
                    self::renderBatch($view);
                    ?>
                </table>
                <input type="hidden" name="task" value=""/>
                <input type="hidden" name="boxchecked" value="0"/>
                <input type="hidden" name="option"
                       value="<?php echo JFactory::getApplication()->input->get('option'); ?>"/>
                <input type="hidden" name="view" value="<?php echo $view->get('name'); ?>"/>
                <?php self::renderHiddenFields($view) ?>
                <?php echo JHtml::_('form.token'); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Renders the search input group if set in the filter xml
     *
     * @param   array &$filters the filters set for the view
     *
     * @return  void
     */
    protected static function renderSearch(&$filters)
    {
        $showSearch = ! empty($filters['filter_search']);
        if ( ! $showSearch) {
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
                    title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>"
                    onclick="document.getElementById('filter_search').value='';">
                <i class="icon-delete"></i>
            </button>
        </div>
        <?php
    }

    /**
     * Renders the table head
     *
     * @param   array &$headers an array containing the table headers
     *
     * @return  void
     */
    protected static function renderHeader(&$headers)
    {
        echo '<tr>';
        foreach ($headers as $header) {
            echo "<th>$header</th>";
        }

        echo '</tr>';
    }

    /**
     * Renders the table head
     *
     * @param   array &$headers an array containing the table headers
     * @param   array &$filters the filters set for the view
     *
     * @return  void
     */
    protected static function renderHeaderFilters(&$headers, &$filters)
    {
        $noFilters = count($filters) === 0;
        $onlySearch = (count($filters) === 1 AND ! empty($filters['filter_search']));
        $dontDisplay = ($noFilters OR $onlySearch);
        if ($dontDisplay) {
            return;
        }

        $headerNames = array_keys($headers);
        echo '<tr>';
        foreach ($headerNames as $name) {
            $name = str_replace('.', '_', $name);
            $found = false;
            $searchName = "filter_$name";
            foreach ($filters as $fieldName => $field) {
                if ($fieldName == $searchName) {
                    echo '<th><div class="js-stools-field-filter">';
                    echo $field->input;
                    echo '</div></th>';
                    $found = true;
                    break;
                }
            }

            if ($found) {
                continue;
            }

            echo '<th></th>';
        }

        echo '</tr>';
    }

    /**
     * Renders the table head
     *
     * @param   array &$items an array containing the table headers
     *
     * @return  void
     */
    protected static function renderBody(&$items)
    {
        if ( ! empty($items['attributes']) AND is_array($items['attributes'])) {
            $bodyAttributes = '';
            foreach ($items['attributes'] AS $bodyAttribute => $bodyAttributeValue) {
                $bodyAttributes .= $bodyAttribute . '="' . $bodyAttributeValue . '" ';
            }

            echo "<tbody $bodyAttributes>";
        } else {
            echo '<tbody>';
        }

        $iteration = 0;
        foreach ($items as $index => $row) {
            if ($index === 'attributes') {
                continue;
            }

            self::renderRow($row, $iteration);
        }

        echo '</thead>';
    }

    /**
     * Renders a row
     *
     * @param   array $row the row to be displayed
     * @param   int &$iteration the current iteration
     *
     * @return  void  outputs HTML
     */
    protected static function renderRow($row, &$iteration)
    {
        // Custom attributes
        if ( ! empty($row['attributes']) AND is_array($row['attributes'])) {
            $rowAttributes = '';
            foreach ($row['attributes'] AS $rowAttribute => $rowAttributeValue) {
                $rowAttributes .= $rowAttribute . '="' . $rowAttributeValue . '" ';
            }

            echo "<tr $rowAttributes>";
        } else {
            // Joomla standard is row0 or row1 for even and odd rows
            echo "<tr class='row" . $iteration % 2 . "'>";
        }

        foreach ($row as $index => $column) {
            // Attributes should not be presented as table data
            if ($index === 'attributes') {
                continue;
            }

            // Custom attributes for table data
            if (is_array($column)) {
                if ( ! empty($column['attributes']) AND is_array($column['attributes'])) {
                    $colAttributes = '';
                    foreach ($column['attributes'] AS $colAttribute => $colAttributeValue) {
                        $colAttributes .= $colAttribute . '="' . $colAttributeValue . '" ';
                    }

                    echo "<td $colAttributes>";
                } else {
                    echo "<td>";
                }

                echo $column['value'];
            } else {
                echo "<td>$column";
            }

            echo "</td>";
        }

        echo '</tr>';
        $iteration++;
    }

    /**
     * Renders the table foot
     *
     * @param   object &$view the view context calling the function
     *
     * @return  void
     */
    protected static function renderFooter(&$view)
    {
        $columnCount = count($view->headers);
        echo '<tfoot><tr>';
        echo "<td colspan='$columnCount'>";
        echo $view->pagination->getListFooter();
        echo '</td></tr></tfoot>';
    }

    /**
     * Renders the batch window
     *
     * @param   object &$view the view context calling the function
     *
     * @return  void
     */
    protected static function renderBatch(&$view)
    {
        if (isset($view->batch) && ! empty($view->batch)) {
            foreach ($view->batch as $name => $path) {
                if (file_exists($path)) {
                    echo $view->loadTemplate($name);
                }
            }
        }
    }

    /**
     * Renders hidden fields
     *
     * @param   object &$view the view object
     *
     * @return  void  outputs hidden fields html
     */
    protected static function renderHiddenFields(&$view)
    {
        if (isset($view->hiddenFields) && ! empty($view->hiddenFields)) {
            foreach ($view->hiddenFields as $field) {
                echo $field;
            }
        }
    }
}
