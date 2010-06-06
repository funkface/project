<?php
class App_View_Helper_FriendlyDateWithTime extends Zend_View_Helper_Abstract
{

    public function friendlyDate($date)
    {
        $date = strtotime($date);
        $date = date('l jS F Y \a\t H:i', $date);

        return $date;
    }
}