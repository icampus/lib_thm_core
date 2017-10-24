<?php
/**
 * @category    Joomla library
 * @package     THM_Core
 * @subpackage  lib_thm_core
 * @author      Andrej Sajenko, <Andrej.Sajenko@mni.thm.de>
 * @copyright   2014 TH Mittelhessen
 * @license     GNU GPL v.2
 * @link        www.mni.thm.de
 */

/**
 * Exception fo a illegal code implementation.
 *
 * @category  Joomla.Library
 * @package   thm_core.exception
 */
class IllegalImplementationException extends Exception
{
    /**
     * Constructor.
     *
     * @param   String $message The exception message
     * @param   int $code The http status code
     * @param   Exception $previous The previous exception
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($$message, $code, $previous);
    }
}
