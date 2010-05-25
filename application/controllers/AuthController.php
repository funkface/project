<?php

class Admin_AuthController extends Zend_Controller_Action
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
            $this->_forward('form');
            return;
        } else {
            $this->_redirect('/admin/auth/logout');
            exit();
        }
    }

    public function formAction()
    {
        $auth = Zend_Auth::getInstance();

        $form = new Admin_Form_Login();

        $this->view->formResponse = '';

        if ($this->getRequest()->isPost()) {
            
            try{
                
                if ($form->isValid($this->getRequest()->getPost())) {
    
                    // Success - update last login and redirect
                    $userTable = new Admin_Model_User;
                    $userTable->updateLastlogin(Zend_Auth::getInstance()->getIdentity()->id);
    
                    $this->_redirect('/admin/');
    
                } else if (count($form->getErrors('id')) > 0) {
    
                    // Failed - hash in form doesn't match that in session (possible CSRF attack)
                    Zend_Auth::getInstance()->clearIdentity();
                    throw new Exception('A problem has occured');
                    return;
                }
                
            }catch(Exception $e){
                
            }

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

        $this->_redirect('/admin/auth/');
    }

}
