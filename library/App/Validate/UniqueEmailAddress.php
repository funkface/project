<?php
class App_Validate_UniqueEmailAddress extends Zend_Validate_EmailAddress
{
    const IN_USE = 'emailAddressInUse';

    protected $_messageTemplates = array(
        self::IN_USE => 'Email address already in use, please choose another.'
    );
    
    public function isValid($value)
    {
        $valid = parent::isValid($value);
        if($valid){
           $user = Model_UserTable::getInstance()->findByEmail($value);
           $valid = !$user;
           $this->_error(self::IN_USE);
        }
        
        return $valid;
    }
    
}