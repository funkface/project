<?php

class Account_AuthController extends Zend_Controller_Action
{
    /**
     * Init controller
     */
    public function init()
    {
    	//$this->_helper->layout->setLayout('noauth');

    	if($this->getRequest()->getActionName() != 'index'){
        	$this->view->messages = $this->_helper->flashMessenger->getMessages();
    	}
    }

    public function indexAction()
    {
        $redirect = array(
            'controller' => 'auth',
            'action' => (null === Zend_Auth::getInstance()->getIdentity()) ? 'login' : 'logout',
        );
        
        $session = new Zend_Session_Namespace('loginRedirect');
        if(isset($session->params['abbr'])){
            $redirect['group'] = $session->params['abbr'];
        }
        
        $this->_helper->redirector->gotoRoute($redirect, 'account');
    }

    public function loginAction()
    {

		$request = $this->getRequest();
		$abbr = $request->getParam('group');
		
		$auth = Zend_Auth::getInstance();
        $form = new Account_Form_Login();
        
        $formAction = array('controller' => 'auth', 'action' => 'login');
        if(!empty($abbr)){
            $formAction['group'] = $abbr;
            $this->view->group = Model_GroupTable::getInstance()->findOneByAbbr($abbr);
        }
        $form->setAction($this->_helper->url->url($formAction, 'account'));
        $redirect = new Zend_Session_Namespace('loginRedirect');
        
        if ($request->isPost()) {

            //try{
                
                if($form->isValid($request->getPost())){
                	
                	// Success - redirect
                	/*
                	$abbr = $request->getParam('group');
                	
                	if($abbr && $group = Model_GroupTable::getInstance()
                		->findOneByAbbr($abbr)
                	){
                		
	                	$this->_helper->redirector->gotoRoute(array(
	                		'group' => $group->abbr
	                	), 'forum');
	                */
                                        
                    //die(print_r($session->deniedRoute, true));
                    
                    if(is_array($redirect->params)){
                        
                        $params = $redirect->params;
                        $name = $redirect->name;
                        $redirect->unsetAll();
                        
                        $this->_helper->redirector->gotoRoute($params, $name);
                        
                	}else if($abbr){
                	    
                	    $this->_helper->redirector->gotoRoute(array(
                	       'controller' => 'index',
                	       'abbr' => $abbr
                	    ), 'forum');
                	    
                	}else{
                	    
	                    $this->_helper->redirector->gotoRoute(array(
	                        'controller' => 'index',
	                        'action' => 'index'
	                    ), 'account');
                	}
                }
                
            //}catch(Exception $e){
                
            //}
            
            // Failed - incorrect details
            $form->addError('Login unsuccessful.');
            $auth->clearIdentity();
        }
        
        $this->view->redirect = $redirect;
        $this->view->form = $form;
        $this->view->urlParams = array('controller' => 'auth');
        if($redirect->params['module'] == 'forum'){
                $this->view->urlParams['group'] = $redirect->params['abbr'];
        }

    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        Zend_Session::destroy();

        $this->_helper->redirector->gotoRoute(array('action' => 'login'), 'account');
    }
    
    public function forgottenAction(){

        $form = new Account_Form_ForgottenPassword();
        $request = $this->getRequest();

        if($request->isPost()){

            $post = $request->getPost();

            if($form->isValid($post)){

                $user = Model_UserTable::getInstance()->findOneByEmail($post['email']);

                if(!$user){
	
                	// More secure to not reveal existence of user accounts
                    //$form->email->addError('User account not found');

                }else{

                    // store reset details, save and send alert
                    $user->resetRequest();
                }
                
                $this->_helper->flashMessenger->addMessage(
                    'If we recognised your email address, a password reset link will have been sent, check your email in a few minutes.'
                );
                $this->_helper->redirector->gotoRoute(array('action' => 'index'), 'account');
                
            }
        }

        $this->view->form = $form;
    }
    
    public function resendActivationAction()
    {
        $form = new Account_Form_ResendActivation();
        $request = $this->getRequest();

        if($request->isPost()){

            $post = $request->getPost();

            if($form->isValid($post)){

                $user = Model_UserTable::getInstance()->findByActivationEmailWithActivation($post['email']);

                if($user){
    
                    // store reset details, save and send alert
                    $user->resendActivation();
                }
                
                $this->_helper->flashMessenger->addMessage(
                    'If we recognised your email address and your account needs activation, 
                    your activation email will have been resent, check your email in a few minutes.'
                );
                $this->_helper->redirector->gotoRoute(array('action' => 'index'), 'account');
                
            }
        }

        $this->view->form = $form;
    }

    public function resetAction(){
        
        $request = $this->getRequest();
        $user = Model_UserTable::getInstance()->findOneByResetCode($request->getParam('code'));

        if($user){

        	// is code still within its expiry
        	$config = Zend_Registry::get('config')->auth->reset;
        	$interval = time() - strtotime($user->reset_request_date);
        	
        	if($interval <= $config->maxInterval){ 

        		$form = new Account_Form_PasswordReset($user);
        		if($request->isPost()){

        			$post = $request->getPost();
        			if($form->isValidWithDoctrineRecord($post, $user)){

        				$user->resetPassword();

        				$this->_helper->flashMessenger->addMessage(
        					'Your password has been successfully reset.'
        				);
        				$this->_helper->redirector->gotoRoute(array('action' => 'index'), 'account');
        			}
        		}

        		$this->view->form = $form;
        		return;

        	}else{

        		// code has expired so delete it, probably not strictly necessary
        		$user->reset_code = null;
        		$user->reset_request_date = null;
        		$user->save();
        	}

        }

    }
    
    public function registerAction()
    {

    	$request = $this->getRequest();
    	$abbr = $request->getParam('group');

    	$groupSelect = (empty($abbr) || !$group = Model_GroupTable::getInstance()->findOneByAbbr($abbr));
    	$form = new Account_Form_Register(array('groupSelect' => $groupSelect));
    	
    	$user = new Model_User();
    	
    	if($request->isPost() && $form->isValidWithDoctrineRecord($request->getPost(), $user)){

    	    if(!$group){
    	        $group = Model_GroupTable::getInstance()->findOneByAbbr($form->abbr->getValue());
    	    }
    	    
    		$user->register($group);
    		
    		$this->_helper->flashMessenger->addMessage(
                'Your registration was successful. Please check your email in a few minutes for your activation link.'
            );
    		

    		$this->_helper->redirector->gotoRoute(array(
                'controller' => 'index',
                'action' => 'index'
            ), 'account');
    	}
    	
    	$this->view->form = $form;

    	$this->view->group = $group;
    }
    
    public function activateAction(){
    	
        $request = $this->getRequest();
    	$code = $request->getParam('code');
    	$abbr = $request->getParam('group');
    	$user = Model_UserTable::getInstance()->findOneByActivationCode($code);
    	
    	if($user){
        	$form = new Account_Form_Activate($user);
        	
        	if(!empty($abbr) && $group = Model_GroupTable::getInstance()->findOneByAbbr($abbr)){
        	    $this->view->group = $group;
        	}
        	
        	if($request->isPost() && $form->isValid($request->getPost())){
        	    
        	    $user->activate();
        	    
        	    $this->_helper->flashMessenger->addMessage(
                    'Your account has been successfully activated. You may now login.'
                );
        	    
        	    $this->_helper->redirector->gotoRoute(array(
                    'controller' => 'index',
                    'action' => 'index'
                ), 'account');
        	}
        	
        	$this->view->form = $form;
    	}
    	$this->view->form = $form;
    }

}
