<?php
class Account_Form_Register extends App_Form
{
    public function init()
    {
        $first = new Zend_Form_Element_Text('first_name');
        $first->setLabel('First name')
            ->setAttrib('class', 'text')
            ->setRequired(true)
            ->addFilter('StringTrim');
                 
        $last = new Zend_Form_Element_Text('last_name');
        $last->setLabel('Last Name')
            ->setAttrib('class', 'text')
            ->setRequired(true)
            ->addFilter('StringTrim');
             
        $membership = new Zend_Form_Element_Text('member_no');
        $membership->setLabel('Membership number')
            ->setAttrib('class', 'text')
            ->addFilter('StringTrim');
            //->addValidator('SsafaNumber');
        
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email address')
            ->setAttrib('class', 'text')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('EmailAddress');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
            ->setAttrib('class', 'password')
            ->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('Password');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Register')
            ->setAttrib('class', 'submit')
            ->removeDecorator('label');

        $this->addElements(array($first, $last, $membership, $email, $password, $submit));
    }
}