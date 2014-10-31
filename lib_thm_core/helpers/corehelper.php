<?php
/**
 * @category    Joomla component
 * @package     THM_Organizer
 * @subpackage  com_thm_organizer.admin
 * @name        provides functions useful to multiple component files
 * @author      James Antrim, <james.antrim@mni.thm.de>
 * @author      Wolf Rost, <wolf.rost@mni.thm.de>
 * @copyright   2014 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.mni.thm.de
 */

/**
 * Class providing functions usefull to multiple component files
 *
 * @category  Joomla.Component.Admin
 * @package   thm_organizer
 */
class THM_CoreHelper
{
    /**
     * Retrieves the two letter language identifier
     *
     * @return  string
     */
    public static function getLanguageShortTag()
    {
        $fullTag = JFactory::getLanguage()->getTag();
        $tagParts = explode('-', $fullTag);
        return $tagParts[0];
    }
}
