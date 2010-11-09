<?php
class Account_Form_Forum extends App_Form
{
    protected $_user;

    public function __construct(Model_User $user)
    {
        $this->_user = $user;
        parent::__construct();
    }
    
    public function init()
    {
        $image = new App_Form_Element_Image('avatar');
        $image->setLabel('Picture')
            ->setImage($this->_user->avatar)
            ->addFilter('ImageCreateVersion', array(
                'full' => array('width' => 200, 'height' => 200),
                'thumb' => array('width' => 100, 'height' => 100),
                'tiny' => array('width' => 30, 'height' => 30),
                'teeny' => array('width' => 16, 'height' => 16, 'method' => 'crop'),
            ));
            
        $body = new App_Form_Element_Tinymce('signature');
        $body->setLabel('Post signature')
            //->addValidator('Identical', array('123'))
            ->setValue($this->_user->signature);
        
        $save = new Zend_Form_Element_Submit('submit');
        $save->setLabel('Update forum settings')
            ->setAttrib('class', 'submit');
        
        $this->addElements(array($image, $body, $save));
    }
}