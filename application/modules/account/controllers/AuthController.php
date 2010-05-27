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
        if (null === Zend_Auth::getInstance()->getIdentity()) {
            $this->_forward('login');
        } else {
            $this->_helper->redirector->gotoRoute(array('action' => 'logout'), 'account');
        }
    }

    public function loginAction()
    {
        $auth = Zend_Auth::getInstance();

        $form = new Account_Form_Login();
        $form->setAction($this->_helper->url->simple('login'));

        if ($this->getRequest()->isPost()) {
            
            try{
                
                if($form->isValid($this->getRequest()->getPost())){
    
                    // Success - update last login and redirect
                    Model_UserTable::getInstance()->updateLastLogin(
                    	Zend_Auth::getInstance()->getIdentity()->id
                    );
                    
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

        $requestForm = new Account_Form_ForgottenPassword();
        $updateForm = new Account_Form_PasswordResetWithCredentials();

        $request = $this->getRequest();

        if($request->isPost()){

            $post = $request->getPost();

            if($requestForm->isValid($post)){

                Model_User::getInstance()->findByEmail($post['email']);

                if(!$user){
	
                	// More secure to not reveal existence of user accounts
                    //$form->email->addError('User account not found');
                    $this->view->requestFormResponse = 'A password reset link has been sent, check your email in a few minutes';
                    return;

                }else{

                    // store reset details and save
                    $user->resetRequest();

                    // send email to user
                    $email = new App_Alert_PasswordReset();
                    $email->send($user);

                    $this->view->requestFormResponse = 'A password reset link has been sent, check your email in a few minutes';
                    return;
                }

            }else if($updateForm->isValid($post)) while(true){

                $user = Model_User::getInstance()->findByEmail($updateForm->getValue('email'));

                if($user){

                    if(!$user->isLocked()){

                        $filter = new App_Form_Filter_EncryptSha1();
                        $oldPassword = $filter->filter($updateForm->getValue('current_password'));

                        if($oldPassword == $user->password){

                            $newPassword = $updateForm->getValue('password');

                            $validatorChain = new Zend_Validate();
                            $validatorChain
                                ->addValidator(new App_Form_Validate_Password($user))
                                ->addValidator(new App_Form_Validate_PasswordHistory($user));

                            if($validatorChain->isValid($newPassword)){

                                $user->password = $filter->filter($newPassword);
                                $user->reset_code = null;
                                $user->reset_request = null;
                                $user->unlock();

                                $passwords = new PasswordAdmin();
                                $passwords->addToHistory($user->id, $oldPassword);
                                
                                Zend_Registry::get('log')->log('reset password', null, null, $user->id);

                                $this->view->updateFormResponse = 'Your password has been successfully reset';
                                return;

                            }else{

                                $updateForm->getElement('password')->addErrors($validatorChain->getMessages());
                                break;
                            }

                        }
                    }

                    $user->loginAttempt();
                }

                $updateForm->getElement('email')->addError('Invalid email and password combination');

                break;
            }
        }

        $this->view->requestFormResponse = 'If you have forgotten your password or if your account has been locked
        due to too many unsuccessful login attempts, enter your email address here to request that a password reset link
        be sent to your inbox.';

        $this->view->updateFormResponse = 'Alternatively, if you remember your password but it has expired, complete the
        form below to set your new password.';

        $this->view->requestForm = $requestForm;
        $this->view->updateForm = $updateForm;

    }

    /*
    public function resetAction(){

        $config = Zend_Registry::get('config')->auth->reset;
        $request = $this->getRequest();
        $passwords = new PasswordAdmin;

        if($code = $this->_helper->input('code')){

            $users = new User();
            if($user = $users->fetchByResetCode(base64_decode($code))){

                $interval = time() - strtotime($user->reset_request);

                // is code still within its expiry
                if($interval <= $config->maxInterval){

                    $form = new forms_PasswordReset($user);
                    if($request->isPost()){

                        $post = $request->getPost();
                        if($form->isValid($post)){

                            if($form->getValue('email') == $user->email){

                                $lastPassword = $user->password;

                                $filter = new App_Form_Filter_EncryptSha1();
                                $user->password = $filter->filter($form->getValue('password'));
                                $user->reset_code = null;
                                $user->reset_request = null;
                                $user->unlock();

                                $passwords->addToHistory($user->id, $lastPassword);

                                $this->view->formResponse = 'Your password has been successfully reset, <a href="/">click here to login</a>';
                                return;

                            }else{

                                $form->email->addError('please enter the email address to which the reset link was sent');
                            }

                        }
                    }

                    $this->view->formResponse = 'Enter your email address and new password here';
                    $this->view->form = $form;
                    return;

                }else{

                    // code has expired so delete it, probably not stricly necessary
                    $user->reset_code = null;
                    $user->reset_request = null;
                    $user->save();
                }

            }

        }

        $this->view->formResponse = 'This reset link is invalid, <a href="/auth/forgotten">click here to request another</a>';

    }
    */

}
