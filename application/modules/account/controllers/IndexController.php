<?php

class Account_IndexController extends Zend_Controller_Action
{
    public function showUserMessagesAction()
    {
        $this->view->messages = $this->_helper->msg->getMessages();

        if (count($this->view->messages) == 0) {
            $this->getHelper('viewRenderer')->setNoRender();
        }
    }

    public function indexAction()
    {

    }
}