<?php
class App_View_Helper_TerseDateWithDayAndTime extends Zend_View_Helper_Abstract
{

    public function terseDateWithDayAndTime($date)
    {
        $date = strtotime($date);
        $date = date('D d/m/Y H:i', $date);

        return $date;
    }
}