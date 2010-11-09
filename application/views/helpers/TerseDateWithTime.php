<?php
class App_View_Helper_TerseDateWithTime extends Zend_View_Helper_Abstract
{

    public function terseDateWithTime($date)
    {
        $date = strtotime($date);
        $date = date('d/m/Y H:i', $date);

        return $date;
    }
}