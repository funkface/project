<?php
class App_Filter_EscapeInlineJsString implements Zend_Filter_Interface
{
    protected $_encoding;
    
    public function __construct($encoding)
    {
        $this->_encoding = $encoding;
    }
    
    public function filter($value)
    {
        $value = stripslashes($value);
        $value = str_replace("'", "\\'", $value);
        $value = htmlspecialchars($value, ENT_QUOTES, $this->_encoding);
        
        return $value;
    }

}