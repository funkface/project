<?php
class Forum_Form_Post extends App_Form
{
    protected $_post;

    public function __construct(Model_Post $post)
    {
        $this->_post = $post;
        parent::__construct();
    }
    
    public function init()
    {
        if($this->_post->isFirst()){
            
            $title = new Zend_Form_Element_Text('title');
            $title->setLabel('Title')
                ->setAttrib('class', 'text')
                ->setValue($this->_post->Topic->title)
                ->addFilter('StringTrim')
                ->setRequired(true);
                
            $this->addElement($title);
        }
        
        $body = new App_Form_Element_Tinymce('body');
        $body->setLabel('Post body')
            ->setValue($this->_post->body);
            
        $image = new App_Form_Element_MultiImage('image', array('images' => $this->_post->Images));
        $image->setLabel('Pictures');
            
        $preview = new Zend_Form_Element_Submit('preview');
        $preview->setLabel('Preview')
            ->setAttrib('class', 'submit');
        
        $send = new Zend_Form_Element_Submit('save');
        $send->setLabel('Post')
            ->setAttrib('class', 'submit');
                    
        $this->addElements(array($body, $image, $preview, $send));
        
        if(!$this->_post->userCanEdit){
            $this->addError('This post is locked and may not be edited.');
        }
    }
}