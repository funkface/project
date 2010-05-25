<?php

class Account_Form_Login extends App_Form
{

    public function init()
    {
    	
    	$action = $this->getView()->url(array('action' => 'login'));
    	
        $this->setAction($action)
             ->setAttrib('id', 'loginForm');

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username')
                 ->setAttrib('class', 'text')
                 ->setRequired(true)
                 ->addFilter('StringTrim')
                 ->addFilter('StripTags')
                 ->addValidator('Authorise')
                 ->removeDecorator('Errors'); // don't show errors on login form


        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
                 ->setAttrib('class', 'password')
                 ->setRequired(true)
                 ->addFilter('StringTrim')
                 ->addFilter('StripTags')
                 ->removeDecorator('Errors');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Login')
               ->setAttrib('class', 'submit')
               ->removeDecorator('label');

        $this->addElements(array($username, $password, $submit));

        /*
        $hash = new Zend_Form_Element_Hash('id');
        $hash->setSalt(Zend_Registry::get('config')->auth->salt)
              ->setDecorators(array(
                    'ViewHelper'
                    ));
        $this->addElement($hash);

        $this->id->removeDecorator('Errors');
        */
    }
}
