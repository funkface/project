<?php
class Account_settingsController extends Zend_Controller_action
{
    protected $_user;
    
    public function init()
    {
        $user = Zend_Auth::getInstance()->getIdentity();
        $this->_user = Model_UserTable::getInstance()->findOneById($user->id);
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
    }
    
	public function detailsAction()
	{
		$form = new Account_Form_Details($this->_user);
        $request = $this->getRequest();
        
        if($request->isPost() && $form->isValid($request->getPost())){
            
            $this->_user->fromArray($form->getValues());
            $user->save();
            
            $this->_helper->flashMessenger->addMessage('Details successfully updated');
            $this->_redirect();
        }
        
        $this->view->form = $form;
	}
	
	public function passwordAction()
	{
		$form = new Account_Form_Details($this->_user);
        $request = $this->getRequest();
        
        if($request->isPost() && $form->isValid($request->getPost())){
            
            $this->_user->fromArray($form->getValues());
            $user->save();
            
            $this->_helper->flashMessenger->addMessage('Details successfully updated');
            $this->_redirect();
        }
        
        $this->view->form = $form;
	}
	
	public function forumAction()
	{
		
	}
	
	public function alertAction()
	{
		
	}
}