<?php

class App_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	$auth = Zend_Auth::getInstance();
    	$moduleName = $request->getModuleName();
    	
        switch($moduleName){
        	
        	case 'default':	
	        	return;
	        	
        	case 'account':
        		if($request->getControllerName() == 'auth'){
        			return;
        		}
        		
        	default:
		        if($auth->hasIdentity() == false){
		            
		            $this->redirect($request);
		            
		        }else{
		            
		            $authUser = $auth->getIdentity();
                    $user = Model_UserTable::getInstance()->findOneById($authUser->id);
                    Zend_Registry::set('user', $user);
                    
                    if($moduleName == 'admin' && !$user->isLeader()){
                        $this->redirect($request);
                    }
		        }
        }
        
    }
    
    protected function redirect(Zend_Controller_Request_Abstract $request)
    {
        $session = new Zend_Session_Namespace('loginRedirect');
        $session->params = $request->getParams();
        $session->name = Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
          
        $request->setModuleName('account')
            ->setControllerName('auth')
            ->setActionName('index');
    }
 }