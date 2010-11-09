<?php

class App_Form_Element_Image extends Zend_Form_Element_File
{
    protected $_image;
    
    public function init()
    {
        // Necessary before additional decorators can be added
        $this->loadDefaultDecorators();

        // Set the viewScript path
        $view = $this->getView();
        $view->addScriptPath(APPLICATION_PATH . '/../library/App/Form/views/scripts/');

        $this->setDestination(Zend_Registry::get('config')->filepath->upload->image)
            ->removeValidator('File_Upload')
            //->addValidator('Extension', false, 'jpeg,jpg,png,gif')
            ->addPrefixPath('App_Filter','App/Filter/', 'filter')
            //->addValidator('StringLength', true, array(0,250))
            ->addFilter('RenameFileWithExtension')
            ->addFilter('ImageCreateVersion', array(
                'full' => array('width' => 550, 'height' => 400),
                'thumb' => array('width' => 100, 'height' => 100),
                'small' => array('width' => 50, 'height' => 50, 'method' => 'crop'),
                'tiny' => array('width' => 30, 'height' => 30, 'method' => 'crop')
            ))
            ->addFilter('Callback', array($this, 'setImageCallback'))
            ->addDecorator('ViewScript', array(
                'viewScript' => '_form_element_image.phtml',
                'placement' => false
            ))
            ->setDescription('Upload an image with one of the following extensions: jpg, jpeg, gif, png.');
    }
    
    public function isValid($value, $context = null)
    {
        
        if(!$this->isUploaded()){
            
            if(isset($context['delete-' . $this->getName()])){
                
                $this->setImage(null);
                return true;
                
            }else if(isset($context['existing-' . $this->getName()])){
                
                $value = $context['existing-' . $this->getName()];
                
                $this->setImage($value);
                $this->setValue($value);
                
                return true;
            }
            
        }
        
        return parent::isValid($value, $context);
    }

    /**
     * Get the thumbnail webpath of the current image
     * @return string
     */
    public function getThumbnail($filename = false)
    {
        if($filename || $filename = $this->getImage()){
            $thumbFilename = 'thumb_' . $filename;
            if(file_exists(
                Zend_Registry::get('config')->filepath->upload->image . DIRECTORY_SEPARATOR . $thumbFilename
            )){
                return $this->getView()->baseUrl(
                    Zend_Registry::get('config')->webpath->upload->image . '/' . $thumbFilename
                );
            }
        }

        return false;
    }
    
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    public function setImage($value)
    {
        $this->_image = $value;
        return $this;
    }
    
    public function getImage()
    {
        return $this->_image;
    }
    
    public function setImageCallback($value)
    {
        $this->setImage($value);
        return $value;
    }

}