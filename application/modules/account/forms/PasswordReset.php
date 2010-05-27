<?php
class Account_Form_PasswordReset extends Zend_Form {

    protected $_user;

    public function __construct($user=false)
    {
        $this->_user = $user;

        parent::__construct();
    }

    public function init(){

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
                 ->setAttrib('class', 'textElement')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('EmailAddress');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('New Password')
                 ->setAttrib('class', 'passwordElement')
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->setRequired(true)
            ->addPrefixPath('App_Validate', 'App/Form/Validate/', 'validate')
            ->addValidator('Password', true, $this->_user)
            ->addValidator('PasswordHistory', true, $this->_user);

        $passwordConfirm = new Zend_Form_Element_Password('password_confirm');
        $passwordConfirm->setLabel('Confirm New Password')
                 ->setAttrib('class', 'passwordElement')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addPrefixPath('App_Validate', 'App/Form/Validate/', 'validate')
            //->addFilter('EncryptSha1')
            ->addValidator('Match', true, array($password));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Set password');

        $this->addElements(array($email, $password, $passwordConfirm, $submit))
        ->addDecorator('FormElements')
        ->addDecorator('HtmlTag', array('tag' => 'dl', 'class' => 'formDl'))
        ->addDecorator('Form');

    }


}
?>