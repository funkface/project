<?php
class Form_Login extends App_Form
{
    
    protected $_elementDecorators = array(
        'ViewHelper',
        array('Description', array('tag' => 'p', 'class' => 'description')),
        'Errors',
        array('HtmlTag', array('tag' => 'dd')),
        array('Label', array('tag' => 'dt'))
    );
    
    public function init()
    {
        $username = new Zend_Form_Element_Text('email');
        $username->setLabel('Email')
            ->setAttrib('id', 'ql_email')
            ->setAttrib('class', 'text')
            ->setRequired(true)
            ->addFilter('StringTrim');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
            ->setAttrib('id', 'ql_password')
            ->setAttrib('class', 'text')
            ->setRequired(true)
            ->addFilter('StringTrim');

        $login = new Zend_Form_Element_Submit('login');
        $login->setLabel('Login now')
            ->setAttrib('class', 'submit');

        $this->addElements(array($username, $password, $login));
            /*->addDisplayGroup(array('email', 'password', 'login'), 'fields', array('legend' => 'Forum login'))
            ->setDisplayGroupDecorators(array(
                'FormElements',
                array('HtmlTag', array('tag' => 'dl')),
                'Fieldset'
            ))
            ->addDecorator('FormElements')
            ->addDecorator('Form');*/
    }

}