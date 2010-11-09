<?php
class App_Validate_UserFullNameMulti extends Zend_Validate_Abstract
{
    const NO_USERS = 'userFullNameMultiNoUsers';
    const BAD_USER = 'userFullNameMultiBadUser';
    
    protected $_messageTemplates = array(
        self::NO_USERS => 'No user names provided.'
    );
    
    protected $_groups;
    protected $_users;
    
    public function __construct(Doctrine_Collection $groups, Doctrine_Collection $users)
    {
        $this->_groups = $groups;
        $this->_users = $users;
    }
    
    public function isValid($value, $context = null)
    {
        if(empty($value)){
            $this->_error(self::NO_USERS);
            return false;
        }
        
        $value = (array)$value;
        $users = Model_UserTable::getInstance()->findUsersByFullNamesAndGroups($value, $this->_groups);
        $valid = true;
        
        foreach($value as $name){
            $found = false;
            foreach($users as $user){
                if(strtolower($user->fullName) == strtolower($name)){
                    $found = true;
                    break;
                }
            }
            if(!$found){
                $messageKey = self::BAD_USER . count($this->_messageTemplates);
                $this->_messageTemplates[$messageKey] = $name . ' is not a valid user.';
                $this->_error($messageKey);
                $valid = false;
            }else{
                $this->_users[] = $user;
            }
        }
        
        return $valid;
    }
}