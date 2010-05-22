<?php

class App_Form_Element_Tinymce extends Zend_Form_Element_Textarea
{
    
    public function init()
    {
        $view = $this->getView();
        $view->headScript()->appendFile($view->baseUrl('/js/tiny_mce/tiny_mce.js'));
        $view->headScript()->appendFile($view->baseUrl('/js/tiny_mce/jquery.tinymce.js'));
        $view->headScript()->appendFile($view->baseUrl('/js/mce-setup.js'));

        $this->addFilter('StringTrim')
             ->addPrefixPath('App_Filter', 'App/Filter/', 'filter')
             ->addFilter('TinymceBadHtml')
             ->addFilter('StripWordFormatting')
             ->setAttrib('cols', 60)
             ->setAttrib('rows', 10)
             ->setAttrib('class', 'rich-text');
    }
    
}

?>