<?php

class App_Filter_DateEnglishToIso implements Zend_Filter_Interface
{
    public function filter($value)
    {
        if (empty($value)) {
            return null;
        }

        $date = new Zend_Date($value, 'en_GB');
        return $date->get();
    }
}