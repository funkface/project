<?php
class App_View_Helper_FriendlyDate extends Zend_View_Helper_Abstract
{

    public function friendlyDate($date)
    {
        $date = strtotime($date);
        $date = date('l jS F Y', $date);

        return $date;
    }
}