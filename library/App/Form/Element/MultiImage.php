<?php
class App_Form_Element_MultiImage extends App_Form_Element_Image
{
    protected $_images;
    
    public function init()
    {
        parent::init();
        
        $this->addDecorator('ViewScript', array(
            'viewScript' => '_form_element_multi_image.phtml',
            'placement' => false
        ))
        ->removeFilter('CopyValue')
        ->addFilter('AddValueToDoctrineCollection', array($this->_images, 'image'));
    }
    
    public function isValid($value, $context = null)
    {
        
        if(isset($context['existing-' . $this->getName()])){
            foreach($context['existing-' . $this->getName()] as $key => $image){
                $this->_images[$key]->image = $image;
            }          
        }
        
        foreach($this->_images as $key => $image){
            if(isset($context['delete-' . $this->getName() . '-' . $key])){
                   $this->_images->remove($key);
            }
        }
        
        return parent::isValid($value, $context);
    }
    
    public function setImages(Doctrine_Collection $images)
    {
        $this->_images = $images;
        return $this;
    }
    
    public function getImages()
    {
        return $this->_images;
    }
}