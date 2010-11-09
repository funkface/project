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

        $controllerName = $request->getControllerName();
        
        if($moduleName == 'account' && $controllerName != 'auth'){
            
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            $viewRenderer->view->account = true;
            
        }else if($moduleName == 'admin'){
            
            $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
            $viewRenderer->view->admin = true;
            
        }

        return $request;
    }
}

?>