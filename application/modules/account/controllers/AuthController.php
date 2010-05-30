<?php

class Account_AuthController extends Zend_Controller_Action
{
    /**
     * Init controller
     */
    public function init()
    {
    	$this->_helper->layout->setLayout('noauth');
    }

    public function indexAction()
    {
        $this->_helper->redirector->gotoRoute(array(
        	'controller' => 'auth',
        	'action' => (null === Zend_Auth::getInstance()->getIdentity()) ? 'login' : 'logout'
        ), 'account');
    }

    public function loginAction()
    {
        $auth = Zend_Auth::getInstance();

        $form = new Account_Form_Login();
        $form->setAction($this->_helper->url->simple('login'));

        if ($this->getRequest()->isPost()) {

            try{
                
                if($form->isValid($this->getRequest()->getPost())){
    
                    // Success - redirect
                    $this->_helper->redirector->gotoRoute(array(
                        'controller' => 'index',
                        'action' => 'index'
                    ), 'account');
                }
                
            }catch(Exception $e){
                
            }
            
            // Failed - incorrect details
            $form->addError('Login unsuccessful.');
            $auth->clearIdentity();
        }
        
        $this->view->form = $form;
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
                    $this->view->formResponse = 'A password reset link has been sent, check your email in a few minutes';
                    return;

                }else{

                    // store reset details and save
                    $user->resetRequest();

                    // send email to user
                    $email = new App_Mail_Alert();
		            $email->setUser($user)
		            	->setViewScript('alert/_password_reset.phtml')
		            	->send();

                    $this->view->formResponse = 'A password reset link has been sent, check your email in a few minutes';
                    return;
                }
            }
        }

        $this->view->formResponse = 'If you have forgotten your password or if your account has been locked
        due to too many unsuccessful login attempts, enter your email address here to request that a password reset link
        be sent to your inbox.';

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
        			if($form->isValid($post)){

        				$user->password = $form->getValue('password');
        				$user->reset_code = null;
        				$user->reset_request_date = null;
        				$user->unlock();

        				$this->view->formResponse = 
        					'Your password has been successfully reset, <a href="' . 
        					$this->_helper->url->simple('index') . '">click here to login</a>';
        				return;
        			}
        		}

        		$this->view->formResponse = 'Enter your email address and new password here';
        		$this->view->form = $form;
        		return;

        	}else{

        		// code has expired so delete it, probably not strictly necessary
        		$user->reset_code = null;
        		$user->reset_request_date = null;
        		$user->save();
        	}

        }

        $this->view->formResponse = 'This reset link is invalid, <a href="' .
        $this->_helper->url->simple('forgotten') . '">click here to request another</a>';

    }

}
