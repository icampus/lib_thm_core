<?php
if (!defined('JPATH_TESTS'))
{
	define('JPATH_TESTS', realpath(dirname(__DIR__)));
}
if (!defined('JPATH_BASE'))
{
	define('JPATH_BASE', realpath(dirname(dirname(dirname(__DIR__)))));
}

// Import the Joomla bootstrap.
require_once JPATH_BASE . '/tests/bootstrapJ3.php';
