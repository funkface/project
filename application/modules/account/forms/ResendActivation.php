<?php
class Account_Form_ResendActivation extends App_Form {
    
    public function init(){

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
	        ->setAttrib('class', 'text')
	        ->setRequired(true)
	        ->addFilter('StringTrim')
	        ->addValidator('EmailAddress')
	        ->setDescription('The email address registered against your account.
	        If you have recently changed your email address in your account settings,
	        this will be your new email address.');

        $submit = new Zend_Form_Element_Submit('request');
        $submit->setLabel('Resend activation email')
        	->setAttrib('class', 'submit');

        $this->addElements(array($email, $submit));

    }
    
    
}
?>