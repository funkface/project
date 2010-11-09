<?php
class App_Mail_Automated extends App_Mail
{
    protected function _init()
    {
        $this->setFrom($this->_config->from->address, $this->_config->from->name);
        $this->setDefaultReplyTo($this->_config->from->address, $this->_config->from->name);
        $this->addViewVars(array('from' => $this->_config->from));
    }    
}