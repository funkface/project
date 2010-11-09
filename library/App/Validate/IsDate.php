<?php
class App_Validate_IsDate extends Zend_Validate_Abstract
{
    const INVALID_DATE = 'invalidDate';

    protected $_messageTemplates = array(
        self::INVALID_DATE => "'%value%' is not a valid date",
    );

    public function isValid($value)
    {
        $this->_setValue($value);
        
        if(strtotime($value) === false){
   
            $this->_error(self::INVALID_DATE);
            return false;
        }
        
        return true;
    }
}