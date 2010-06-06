<?php

class App_Controller_Plugin_ModuleSetup extends Zend_Controller_Plugin_Abstract
{

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        if ($request->isXmlHttpRequest()) {
        	
            Zend_Layout::startMvc(array());
            $layout = Zend_Layout::getMvcInstance();
            $layout->disableLayout();
            return;
            
        }

        $moduleName = $request->getModuleName();

        if ($moduleName != 'default') {

            Zend_Layout::startMvc(array());
            $layout = Zend_Layout::getMvcInstance();
            $layout->setLayoutPath('../application/modules/' . $moduleName . '/views/layouts/')
            	->setLayout($moduleName);
        }

        return $request;
    }
}

?>