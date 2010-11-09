<?php
class Account_Form_ForgottenPassword extends App_Form {
    
    public function init(){

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
	        ->setAttrib('class', 'text')
	        ->setRequired(true)
	        ->addFilter('StringTrim')
	        ->addValidator('EmailAddress')
	        ->setDescription('The email address registered against your account.');

        $submit = new Zend_Form_Element_Submit('request');
        $submit->setLabel('Request reset link')
        	->setAttrib('class', 'submit');

        $this->addElements(array($email, $submit));

    }
    
    
}
?>