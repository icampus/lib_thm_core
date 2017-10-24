<?php
/**
 * @category    Joomla library
 * @package     THM_Core
 * @subpackage  lib_thm_core
 * @author      Andrej Sajenko, <Andrej.Sajenko@mni.thm.de>
 * @copyright   2013 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.mni.thm.de
 */

/**
 * A Filter to filter results.
 *
 * @category  Joomla.Library
 * @package   thm_core.util.filter
 */
interface THMIFilter
{
    /**
     * Method to filter a element.
     *
     * @param   Object $obj The object which should be accepted.
     *
     * @return bool true if object is accepted by this filter. false if not.
     */
    public function accept($obj);
}
