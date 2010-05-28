<?php
class App_Mail_Alert extends App_Mail
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
        $config = Zend_Registry::get('config')->email->alert;
        $user = $this->getUser();

        $this->addTo($user->email, $user->first_name . ' ' . $user->last_name);
        $this->setFrom($config->from->address, $config->from->name);
        $this->setReplyTo($config->from->address, $config->from->name);

        $this->addViewVars(array('to' => $user, 'from' => $config->from));
    }
            
}