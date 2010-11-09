<?php
class Form_Contact extends App_Form
{
    public function init()
    {
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name')
            ->setAttrib('class', 'text')
            ->addFilter('StringTrim');
            
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email address')
            ->setAttrib('class', 'text')
            ->setRequired(true)
            ->addValidator('EmailAddress')
            ->addFilter('StringTrim');
            
        $telephone = new Zend_Form_Element_Text('telephone');
        $telephone->setLabel('Telephone number')
            ->setAttrib('class', 'text')
            ->addFilter('StringTrim');
            
        $address = new Zend_Form_Element_Textarea('address');
        $address->setLabel('Address')
            ->setAttrib('rows', 5)
            ->setAttrib('cols', 33)
            ->addFilter('StringTrim');
        
        $query = new Zend_Form_Element_Textarea('query');
        $query->setLabel('Your comment or question')
            ->setAttrib('rows', 8)
            ->setAttrib('cols', 33)
            ->addFilter('StringTrim');
            
        $send = new Zend_Form_Element_Submit('submit');
        $send->setLabel('Send Message')
            ->setAttrib('class', 'submit');
            
        $this->addElements(array($name, $email, $telephone, $address, $query, $send));
    }
}