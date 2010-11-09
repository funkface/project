<?php
class Account_Form_Register extends App_Form
{

    protected $_groupSelect = false;
    
    protected function setGroupSelect($groupSelect)
    {
        $this->_groupSelect = (bool)$groupSelect;
    }
    
    public function init()
    {
        if($this->_groupSelect){
            $groups = array();
            foreach(Model_GroupTable::getInstance()->findAll() as $group){
                $groups[$group->abbr] = $group->title;
            }
            
            $group = new Zend_Form_Element_Select('abbr');
            $group->setLabel('Group')
                ->setDescription('Please choose the group you would like to join.')
                ->setMultiOptions($groups);
                
            $this->addElement($group);
        }
        
        $first = new Zend_Form_Element_Text('first_name');
        $first->setLabel('First name(s)')
        	->setDescription('Your first name, or any forenames.')
            ->setAttrib('class', 'text')
            ->setRequired(true)
            ->addFilter('StringTrim');
            
        $this->_doctrineErrorTemplates['regexp:first_name'] = 
            'Your first name(s) should not contain commas(,), semicolons(;) or carriage returns.';
                 
        $last = new Zend_Form_Element_Text('last_name');
        $last->setLabel('Last name')
        	->setDescription('Your last name.')
            ->setAttrib('class', 'text')
            ->setRequired(true)
            ->addFilter('StringTrim');
            
        $this->_doctrineErrorTemplates['regexp:last_name'] = 
            'Your last name should not contain commas(,), semicolons(;) or any whitespace characters.';
            
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email address')
        	->setDescription('Your Email address, this will also be used for logging in.')
            ->setAttrib('class', 'text')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('UniqueEmailAddress');
            
        $emailConfirm = new Zend_Form_Element_Text('email_confirm');
        $emailConfirm->setLabel('Confirm email address')
        	->setDescription('Your email address again, just to make sure we\'ve got it correct.')
            ->setAttrib('class', 'password')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('Match', true, array($email));
            
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
        	->setDescription(
        		'Enter your chosen password, it should contain letters, 
        		at least one number and be between 8 and 32 characters in length.')
            ->setAttrib('class', 'password')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('Password');

            
        $passwordConfirm = new Zend_Form_Element_Password('password_confirm');
        $passwordConfirm->setLabel('Confirm new Password')
        	->setDescription('Please enter your password again, so we can make sure we\'ve recorded it accurately.')
            ->setAttrib('class', 'password')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('Match', true, array($password));          

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Register now')
            ->setAttrib('class', 'submit')
            ->removeDecorator('label');

        $this->addElements(array(
            $first, $last, //$membership, 
            $email, $emailConfirm, $password, $passwordConfirm, $submit
        ));
    }
}