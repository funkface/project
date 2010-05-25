<?php

class Zend_View_Helper_DisplayDate
{
    function displayDate($timestamp, $format=Zend_Date::DATE_LONG, $withTime=false)
    {
        if ($timestamp == '0000-00-00 00:00:00') {
            return '';
        }

        $date = new Zend_Date($timestamp, 'en_GB');
        if ($withTime) {
            return $date->get($format).' '.$date->get(Zend_Date::HOUR) .':'.$date->get(Zend_Date::MINUTE);
        } else {
            return $date->get($format);
        }
    }
}