<?php
class Account_Form_Message extends App_Form
{
    protected $_message;

    public function __construct(Model_Message $message)
    {
        $this->_message = $message;
        parent::__construct();
    }
    
    public function init()
    {
        $to = new App_Form_Element_MultiAutocomplete('to', array(
            'groups' => $this->_message->From->Groups,
            'recipients' => $this->_message->To
        ));
        $to->setLabel('To')
            ->setRequired(true)
            ->setDescription('Enter the full name of each user you would like to message on a seperate line 
            in the format "Firstname Lastname"');
        
        $subject = new Zend_Form_Element_Text('subject');
        $subject->setLabel('Subject')
            ->setAttrib('class', 'text')
            ->setValue($this->_message->subject)
            ->addFilter('StringTrim')
            ->setRequired(true);
        
        $body = new App_Form_Element_Tinymce('body');
        $body->setLabel('Message body')
            ->setValue($this->_message->body);
            
        $image = new App_Form_Element_MultiImage('image', array('images' => $this->_message->Images));
        $image->setLabel('Picture');
            
        $preview = new Zend_Form_Element_Submit('preview');
        $preview->setLabel('Preview')
            ->setAttrib('class', 'submit');
        
        $send = new Zend_Form_Element_Submit('send');
        $send->setLabel('Send')
            ->setAttrib('class', 'submit');
                    
        $this->addElements(array($to, $subject, $body, $image, $preview, $send));
    }
}