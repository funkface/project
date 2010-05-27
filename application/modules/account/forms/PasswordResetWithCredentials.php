<?php
class Account_Form_PasswordResetWithCredentials extends Zend_Form
{

    public function init()
    {

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
            ->setAttrib('class', 'textElement')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('EmailAddress');

        $existingPassword = new Zend_Form_Element_Password('current_password');
        $existingPassword->setLabel('Current Password')
            ->setAttrib('class', 'passwordElement')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setRequired(true);

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('New Password')
            ->setAttrib('class', 'passwordElement')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setRequired(true);

        $passwordConfirm = new Zend_Form_Element_Password('password_confirm');
        $passwordConfirm->setLabel('Confirm New Password')
            ->setAttrib('class', 'passwordElement')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addPrefixPath('App_Validate', 'App/Validate/', 'validate')
            ->addValidator('Match', true, array($password));

        $submit = new Zend_Form_Element_Submit('reset');
        $submit->setLabel('Set new password')
            ->setRequired(true);

        $this->addElements(array($email, $existingPassword, $password, $passwordConfirm, $submit));

    }
}