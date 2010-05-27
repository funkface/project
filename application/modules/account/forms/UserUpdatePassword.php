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
            ->setAttrib('class', 'passwordElement')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setRequired(true);

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('New Password')
            ->setAttrib('class', 'passwordElement')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setRequired(true)
            ->addValidator('Password', true, $this->_user)
            ->addValidator('PasswordHistory', true, $this->_user);

        $passwordConfirm = new Zend_Form_Element_Password('password_confirm');
        $passwordConfirm->setLabel('Confirm New Password')
            ->setAttrib('class', 'passwordElement')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            //->addPrefixPath('App_Filter','App/Form/Filter', 'filter')
            //->addFilter('EncryptSha1')
            ->addValidator('Match', true, array($password));

        $save = new Zend_Form_Element_Submit('save');
        $save->setLabel('save');

        $this->addElements(array(
            $existingPassword,
            $password,
            $passwordConfirm,
            $save
        ));
    }
}
?>