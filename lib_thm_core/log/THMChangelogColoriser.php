<?php

$doc = JFactory::getDocument();
$doc->addStyleSheet(dirname(__FILE__) . '/THMChangelogColoriser.css');

class THMChangelogColoriser
{
    public static function colorise($file, $onlyLast = false)
    {
        $ret = '';

        $lines = file($file);

        if(empty($lines))
        {
            return $ret;
        }

        array_shift($lines);

        foreach($lines as $line) {

            $line = trim($line);
            if(empty($line)) continue;
            $type = substr($line,0,1);
            switch($type) {
                case '=':
                    continue;
                    break;

                case '+':
                    $ret .= "\t".'<li class="THM-iCampus-added"><span></span>'.htmlentities(trim(substr($line,2)))."</li>\n";
                    break;

                case '-':
                    $ret .= "\t".'<li class="THM-iCampus-removed"><span></span>'.htmlentities(trim(substr($line,2)))."</li>\n";
                    break;

                case '~':
                    $ret .= "\t".'<li class="THM-iCampus-changed"><span></span>'.htmlentities(trim(substr($line,2)))."</li>\n";
                    break;

                case '!':
                    $ret .= "\t".'<li class="THM-iCampus-important"><span></span>'.htmlentities(trim(substr($line,2)))."</li>\n";
                    break;

                case '#':
                    $ret .= "\t".'<li class="THM-iCampus-fixed"><span></span>'.htmlentities(trim(substr($line,2)))."</li>\n";
                    break;

                default:
                    if(!empty($ret)) {
                        $ret .= "</ul>";
                        if($onlyLast) return $ret;
                    }
                    if(!$onlyLast) $ret .= "<h3 class=\"THM-iCampus\">$line</h3>\n";
                    $ret .= "<ul class=\"THM-iCampus\">\n";
                    break;
            }
        }

        return $ret;
    }
}