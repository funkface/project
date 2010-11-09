<?php
class Account_Form_Activate extends App_Form
{
    protected $_user;


    public function __construct(Model_User $user)
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
            	$this->_user->activation_email,
            	'messages' => array(
            		Zend_Validate_Identical::NOT_SAME => 
            		'please enter the email address to which the activation link was sent'
            	)

           	))
           	->setDescription('The email address to which the activation link was sent');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Activate account')
        	->setAttrib('class', 'submit');

        $this->addElements(array($email, $submit));
    }
}