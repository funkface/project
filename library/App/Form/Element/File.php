<?php

class App_Form_Element_File extends Zend_Form_Element_File
{
    public function init()
    {
        // Necessary before additional decorators can be added
        $this->loadDefaultDecorators();

        // Set the viewScript path
        $view = $this->getView();
        $view->addScriptPath(APPLICATION_PATH .'/../library/App/Form/views/scripts/');
        $this->setDestination(Zend_Registry::get('config')->filepath->upload->file)
            ->removeValidator('File_Upload')
             //->addValidator('Extension', false, 'swf,jpg,png,gif')
             ->addPrefixPath('App_Filter','App/Filter/', 'filter')
             ->addFilter('RenameFileWithExtension')
             ->addDecorator('ViewScript', array(
                    'viewScript' => '_form_element_file.phtml',
                    'placement' => false
                ))
             ->setDescription('Upload a file with one of the following extensions: swf, jpg, jpeg, gif, png');
    }

    // Override setValue (disabled in Zend_Form_Element_File)
    public function setValue($value)
    {
        $this->_value = $value;
    }

}