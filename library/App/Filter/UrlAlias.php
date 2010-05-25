<?php
class App_Filter_UrlAlias implements Zend_Filter_Interface
{
    public function filter($value)
    {
        return strtolower(preg_replace(array('/[^\w\s\-_]+|^[\s\-_]+|[\s\-_]+$/', '/[\s\-_]+/'), array('', '-'), $value));
    }
}
?>