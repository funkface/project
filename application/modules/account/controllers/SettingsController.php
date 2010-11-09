<?php
class Account_SettingsController extends Zend_Controller_action
{
    protected $_user;
    
    public function init()
    {
        $this->_user = Zend_Registry::get('user');
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
    }
    
	public function detailsAction()
	{
		$form = new Account_Form_Details($this->_user);
        $request = $this->getRequest();
        
        if($request->isPost() && $form->isValidWithDoctrineRecord($request->getPost(), $this->_user)){

            $this->_user->updateDetails();
            
            $this->_helper->flashMessenger->addMessage('Details successfully updated');
            $this->_helper->redirector->gotoRoute(array(), 'account');
        }
        
        $this->view->form = $form;
	}
	
	public function passwordAction()
	{
		$form = new Account_Form_Password($this->_user);
        $request = $this->getRequest();
        
        if($request->isPost() && $form->isValidWithDoctrineRecord($request->getPost(), $this->_user)){
            
            $this->_user->save();
            
            $this->_helper->flashMessenger->addMessage('Password successfully updated');
            $this->_helper->redirector->gotoRoute(array(), 'account');
        }
        
        $this->view->form = $form;
	}
	
	public function forumAction()
	{
		$form = new Account_Form_Forum($this->_user);
        $request = $this->getRequest();
        
        if($request->isPost() && $form->isValidWithDoctrineRecord($request->getPost(), $this->_user)){
            
            $this->_user->save();
            
            $this->_helper->flashMessenger->addMessage('Forum settings successfully updated');
            $this->_helper->redirector->gotoRoute(array(), 'account');
        }
        
        $this->view->form = $form;
	}
	
	public function alertAction()
	{
		
	}
	
	public function notifySystemUpdateAction()
	{
	    $users = Model_UserTable::getInstance()->findByNullPassword();
	    foreach($users as $user){
	        $user->notifySystemUpdate();
	    }
	    
	    $this->view->users = $users;
	}
}