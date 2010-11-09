<?php
class Account_Form_Details extends App_Form
{
    protected $_user;

    public function __construct(Model_User $user)
    {
        $this->_user = $user;
        parent::__construct();
    }
    
    public function init()
    {
        $first = new Zend_Form_Element_Text('first_name');
        $first->setLabel('First name(s)')
            ->setDescription('Your first name, or any forenames.')
            ->setAttrib('class', 'text')
            ->setValue($this->_user->first_name)
            ->addFilter('StringTrim')
            ->setRequired(true);
            
        $this->_doctrineErrorTemplates['regexp:first_name'] = 
            'Your first name(s) should not contain commas(,), semicolons(;) or carriage returns.';
                 
        $last = new Zend_Form_Element_Text('last_name');
        $last->setLabel('Last Name')
            ->setDescription('Your last name.')
            ->setAttrib('class', 'text')
            ->setValue($this->_user->last_name)
            ->addFilter('StringTrim')
            ->setRequired(true);
            
        $this->_doctrineErrorTemplates['regexp:last_name'] = 
            'Your last name should not contain commas(,), semicolons(;) or any whitespace characters.';
        
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email address')
            ->setDescription('Your Email address, this will also be used for logging in.')
            ->setAttrib('class', 'text')
            ->setValue($this->_user->email)
            ->addFilter('StringTrim')
            ->addValidator('EmailAddress')
            ->setRequired(true);
            
        $emailConfirm = new Zend_Form_Element_Text('email_confirm');
        $emailConfirm->setLabel('Confirm email address')
            ->setDescription('Your email address again, just to make sure we\'ve got it correct.')
            ->setAttrib('class', 'password')
            ->setValue($this->_user->email)
            ->addFilter('StringTrim')
            ->addValidator('Match', true, array($email))
            ->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Update details')
            ->setAttrib('class', 'submit')
            ->removeDecorator('label');

        $this->addElements(array($first, $last, //$membership,
        $email, $emailConfirm, $password, $submit));
    }
}