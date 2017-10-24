<?php
/**
 * @category    Joomla library
 * @package     THM_Repo
 * @subpackage  lib_thm_repo
 * @author      Andrej Sajenko, <Andrej.Sajenko@mni.thm.de>
 * @copyright   2013 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.mni.thm.de
 */

/**
 * Actions.
 *
 * @category  Joomla.Library
 * @package   thm_repo-core
 */
final class THMPermission
{
    const GRANT = 1;
    const DENY = 0;
    const INHERIT = -1;

    /**
     * Check is a value a valid perm value.
     *
     * @param   THMPermission ::value  $permValue  Perm value.
     *
     * @return bool true if a valid perm value.
     */
    public static function isValidPermission($permValue)
    {
        return $permValue === self::DENY
            || $permValue === self::GRANT
            || $permValue === self::INHERIT;
    }
}
