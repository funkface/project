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
            ->setAttrib('class', 'textElement')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('EmailAddress');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('New Password')
            ->setAttrib('class', 'passwordElement')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('Password');

        $passwordConfirm = new Zend_Form_Element_Password('password_confirm');
        $passwordConfirm->setLabel('Confirm New Password')
            ->setAttrib('class', 'passwordElement')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('Match', true, array($password));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Set password');

        $this->addElements(array($email, $password, $passwordConfirm, $submit));
    }
}