<?php
class App_Mail_Contact extends App_Mail_Automated
{
    protected function _preRender()
    {
        $config = $this->_config->contact;

        $email = $config->useTestAddress ? $config->testAddress : $config->address;
        $this->addTo($email, $config->name);
        
        $this->addViewVars(array('to' => $config->contact));
    }         
}