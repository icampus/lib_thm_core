<?php
defined('_JEXEC') or die;
$extScripts = array();
//if (JDEBUG)
//{
//    $extScripts[] = JURI::root()."libraries/extjs4/extjs/ext-all-debug.js";
//}
//else
//{
//    $extScripts[] = JURI::root()."libraries/extjs4/extjs/ext-all.min.js";
//}
$extScripts[] = JURI::root()."libraries/thm_core/js/extjs/ext-all-debug.js";
$doc = JFactory::getDocument();
$scripts = array_keys($doc->_scripts);
foreach ($extScripts as $script)
{
    if (!in_array($script, $scripts))
    {
        $doc->addScript($script);
    }
}