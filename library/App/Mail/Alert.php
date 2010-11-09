<?php
class App_Mail_Alert extends App_Mail_Automated
{
    protected $_user;
    
    public function setUser(Model_User $user)
    {
        $this->_user = $user;
        return $this;
    }
    
    public function getUser()
    {
        if($this->_user == null){
            throw new App_Exception('A user must be set to use App_Mail_Alert');
        }
        
        return $this->_user;
    }
    
    protected function _preRender()
    {
        $config = $this->_config->alert;
        $user = $this->getUser();

        $email = $config->useTestAddress ? $config->testAddress : $user->email;
        $this->addTo($email, $user->fullName);

        $this->addViewVars(array('to' => $user));
    }         
}