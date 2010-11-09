<?php

class Account_Form_Login extends App_Form
{

    public function init()
    {
    	
        $this->setAttrib('id', 'loginForm');

        $username = new Zend_Form_Element_Text('email');
        $username->setLabel('Email address')

            ->setDescription('The email address registered against your account.')
            ->setAttrib('class', 'text')
            ->setRequired(true)
            ->addFilter('StringTrim');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
            ->setDescription('Your current password.')
            ->setAttrib('class', 'password')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('AuthoriseWithLockOut', true, array('Model_User', 'email'));

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
