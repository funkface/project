<?php
class App_Validate_TwitterName extends Zend_Validate_Abstract
{
    const INVALID_TWITTER_NAME = 'invalidTwitterName';

    protected $_messageTemplates = array(
        self::INVALID_TWITTER_NAME => "'%value%' is not a valid Twitter Username",
    );

    public function isValid($value)
    {
        $valueString = (string) $value;
        $this->_setValue($valueString);
        
        if(strlen($value) > 15 || preg_match('/[^a-zA-Z0-9_]/', $value)){
            
            $this->_error(self::INVALID_TWITTER_NAME);
            return false;
        }
        
        return true;
    }
}