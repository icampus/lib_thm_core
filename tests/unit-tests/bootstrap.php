<?php
if (!defined('JPATH_TESTS'))
{
	define('JPATH_TESTS', realpath(dirname(__DIR__)));
}
if (!defined('JPATH_BASE'))
{
	define('JPATH_BASE', realpath(dirname(dirname(dirname(__DIR__)))));
}
if (!defined('JPATH_PLATFORM'))
{
    define('JPATH_PLATFORM', JPATH_BASE . '/libraries');
}
if (!defined('JPATH_LIBRARIES'))
{
    define('JPATH_LIBRARIES', JPATH_BASE . '/libraries');
}

// Import the Joomla bootstrap.
require_once JPATH_BASE . '/tests/bootstrapJ3.php';
