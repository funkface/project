<?php
class Account_Form_Password extends App_Form
{
    protected $_user;

    public function __construct(Model_User $user)
    {
        $this->_user = $user;
        parent::__construct();
    }

    public function init()
    {
        
        $oldPassword = new Zend_Form_Element_Password('old_password');
        $oldPassword->setLabel('Current password')
            ->setDescription('We ask you to enter your existing password to minimise 
            the chance of unauthorised password changing.')
            ->setAttrib('class', 'password')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addFilter('EncryptSha1')
            ->addValidator('Identical', true, array(
                $this->_user->password,
                'messages' => array(
                    Zend_Validate_Identical::NOT_SAME => 
                    'Please enter your existing password.'
                )
            ));
        
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('New Password')
            ->setDescription(
                'Enter your chosen password, it should contain letters, 
                at least one number and be between 8 and 32 characters in length.')
            ->setAttrib('class', 'password')
            ->addFilter('StringTrim')
            ->addValidator('Password')
            ->setRequired(true);

        $passwordConfirm = new Zend_Form_Element_Password('password_confirm');
        $passwordConfirm->setLabel('Confirm New Password')
            ->setDescription('Please enter your password again, so we can make sure we\'ve recorded it accurately.')
            ->setAttrib('class', 'password')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('Match', true, array($password));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Set password')
        	->setAttrib('class', 'submit');

        $this->addElements(array($oldPassword, $password, $passwordConfirm, $submit));
    }
}