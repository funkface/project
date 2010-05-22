<?php
class App_View_Helper_TerseDate extends Zend_View_Helper_Abstract
{

    public function terseDate($date)
    {
        $date = strtotime($date);
        $date = date('d/m/Y', $date);

        return $date;
    }
}