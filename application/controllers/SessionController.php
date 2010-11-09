<?php

class SessionController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function panelAction()
    {
        if($this->view->userLoggedIn = (boolean)$user = Zend_Auth::getInstance()->getIdentity()){
            $this->view->user = Model_UserTable::getInstance()->findOneById($user->id);
        }else{
            $form = new Form_Login();
            $form->setAction($this->_helper->url->simple('login', 'auth', 'account'));
            $this->view->form = $form;
        }
    }
}

