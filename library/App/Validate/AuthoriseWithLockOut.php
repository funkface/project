<?php

class App_Validate_AuthoriseWithLockOut extends App_Validate_Authorise
{

    public function isValid($value, $context = null)
    {
        $valid = $this->_validate($value, $context);
        $records = $this->_authAdapter->getIdentityRecords();
        
        if($valid){
            
            if(!$records[0]->isActive() || $records[0]->isLocked()){ 
                $this->_error(self::NOT_AUTHORISED);
            }else{
                $this->_writeSession();
                $records[0]->loginSuccess();
                return true;
            }
        }
        
        foreach($records as $record){
            $record->loginAttempt();
        }
        
        return false;
    }
}