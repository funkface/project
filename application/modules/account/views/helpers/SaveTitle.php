<?php

class Zend_View_Helper_SaveTitle
{
    protected $_view;

    function saveTitle()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        if ($request->getControllerName()=='edit') {
            return 'Preview and Save';
        }
        return 'Save';
    }
}