<?php

class App_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	$auth = Zend_Auth::getInstance();
    	
        switch($request->getModuleName()){
        	
        	case 'default':	
	        	return;
	        	
        	case 'account':
        		if($request->getControllerName() == 'auth'){
        			return;
        		}
        		
        	default:
		        if($auth->hasIdentity() == false){
		            $request->setModuleName('account')
		            	->setControllerName('auth')
		                ->setActionName('index');
		        }
        }
        
    }
 }