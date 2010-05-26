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

        $this->view->formResponse = '';

        if ($this->getRequest()->isPost()) {
            
            //try{
                
                if ($form->isValid($this->getRequest()->getPost())) {
    
                    // Success - update last login and redirect
                    Model_UserTable::getInstance()->updateLastLogin(
                    	Zend_Auth::getInstance()->getIdentity()->id
                    );
                    
                    $this->_helper->redirector->gotoRoute(array(
                        'controller' => 'index',
                        'action' => 'index'
                    ), 'account');
    
                } else if (count($form->getErrors('id')) > 0) {
    
                    // Failed - hash in form doesn't match that in session (possible CSRF attack)
                    Zend_Auth::getInstance()->clearIdentity();
                    throw new Exception('A problem has occured');
                    return;
                }
                
            //}catch(Exception $e){
                
            //}

            // Failed - incorrect details
            $this->view->formResponse = 'Login unsuccessful.';
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

}
