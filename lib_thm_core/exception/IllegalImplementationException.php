<?php
/**
 * Created by PhpStorm.
 * User: andrej
 * Date: 8/18/14
 * Time: 8:41 PM
 */

class IllegalImplementationException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($$message, $code, $previous);
    }
} 