<?php

class Account_Form_UserUpdatePassword extends App_Form
{
    protected $_user;

    public function __construct($user)
    {
        $this->_user = $user;
        parent::__construct(array());
    }

    public function init()
    {

        $existingPassword = new Zend_Form_Element_Password('current_password');
        $existingPassword->setLabel('Your Current Password')
            ->setAttrib('class', 'password')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('Identical', true, array(
            	$this->user->password,
            	'messages' => array(
            		Zend_Validate_Identical::NOT_SAME => 
            		'please enter your current password.'
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

        $save = new Zend_Form_Element_Submit('save');
        $save->setLabel('save')
        	->setAttrib('class', 'submit');

        $this->addElements(array(
            $existingPassword,
            $password,
            $passwordConfirm,
            $save
        ));
    }
}
?>