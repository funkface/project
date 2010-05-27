<?php
class Account_Form_ForgottenPassword extends Zend_Form {
    
    public function init(){

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
        ->setAttrib('class', 'textElement')
        ->setRequired(true)
        ->addFilter('StringTrim')
        ->addValidator('EmailAddress');

        $submit = new Zend_Form_Element_Submit('request');
        $submit->setLabel('Request reset link')
        ->setRequired(true);

        $this->addElements(array($email, $submit))
        ->addDecorator('FormElements')
        ->addDecorator('HtmlTag', array('tag' => 'dl', 'class' => 'formDl'))
        ->addDecorator('Form');

    }
    
    
}
?>