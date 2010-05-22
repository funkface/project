<?php

class App_Form_Element_Image extends Zend_Form_Element_File
{
    public function init()
    {
        // Necessary before additional decorators can be added
        $this->loadDefaultDecorators();

        // Set the viewScript path
        $view = $this->getView();
        $view->addScriptPath(APPLICATION_PATH .'/../library/App/Form/views/scripts/');

        $this->setDestination(Zend_Registry::get('config')->filepath->upload->image)
            ->removeValidator('File_Upload')
            //->addValidator('Extension', false, 'jpeg,jpg,png,gif')
            ->addPrefixPath('App_Filter','App/Filter/', 'filter')
            //->addValidator('StringLength', true, array(0,250))
            ->addFilter('RenameFileWithExtension')
            ->addFilter('ImageCreateVersion', array(
                'thumb' => array('height' => 100, 'width' => 100),
                'tiny' => array('width' => 30, 'height' => 30)
            ))
            ->addDecorator('ViewScript', array(
                'viewScript' => '_form_element_image.phtml',
                'placement' => false
            ))
            ->setDescription('Upload an image with one of the following extensions: jpg, jpeg, gif, png.');
    }

    /**
     * Get the thumbnail webpath of the current image
     * @return string
     */
    public function getThumbnail()
    {
        if ($filename = $this->getValue()) {
            $thumbFilename = 'thumb_' . $filename;
            if (file_exists(Zend_Registry::get('config')->filepath->upload->image . DIRECTORY_SEPARATOR . $thumbFilename)) {
                return Zend_Registry::get('config')->webpath->upload->image . '/' . $thumbFilename;
            }
        }

        return false;
    }

    // Override setValue (disabled in Zend_Form_Element_File)
    public function setValue($value)
    {
        $this->_value = $value;
    }

}