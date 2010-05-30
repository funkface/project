<?php
class Account_Form_PasswordReset extends App_Form
{
    protected $_user;

    public function __construct($user = false)
    {
        $this->_user = $user;
        parent::__construct();
    }

    public function init()
    {
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
            ->setAttrib('class', 'text')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('EmailAddress')
            ->addValidator('Identical', true, array(
            	$this->_user->email,
            	'messages' => array(
            		Zend_Validate_Identical::NOT_SAME => 
            		'please enter the email address to which the reset link was sent'
            	)
           	));

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('New Password')
            ->setAttrib('class', 'password')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('Password');

        $passwordConfirm = new Zend_Form_Element_Password('password_confirm');
        $passwordConfirm->setLabel('Confirm New Password')
            ->setAttrib('class', 'password')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('Match', true, array($password));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Set password')
        	->setAttrib('class', 'submit');

        $this->addElements(array($email, $password, $passwordConfirm, $submit));
    }
}