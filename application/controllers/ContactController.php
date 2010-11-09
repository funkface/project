<?php

class ContactController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $this->view->form = new Form_Contact();
        
        if($request->isPost() && $this->view->form->isValid($request->getPost())){
            
            $mail = new App_Mail_Contact();
            $mail->setViewScript('alert/_contact.phtml')
                ->setReplyTo($this->view->form->getValue('email'), $this->view->form->getValue('name'))
                ->addViewVars(array('form' => $this->view->form))
                ->send();
                
            $this->_helper->flashMessenger->addMessage('Your message has been sent. 
            If appropriate, someone will endeavour to get back to you soon.');
            $this->_helper->redirector->gotoRoute(array(
                'action' => 'index', 
                'controller' => 'contact'
            ), 'default');
            
        }
    }


}

