<?php
class Account_MessageController extends Zend_Controller_Action
{
    protected $_user;
    
    public function init()
    {
        $this->_user = Zend_Registry::get('user');
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
    }
    
    public function inboxAction()
    {        
        $request = $this->getRequest();
        
        $itemsPerPage = $request->getParam('items');
        $currentPage = $request->getParam('page');
        $sortBy = $request->getParam('sort');
        
        $sortableColumns = array(
            'from' => array('f.last_name', 'f.first_name'),
            'subject' => 'm.subject',
            'received' => 'm.sent_date'
        );
        $defaultColumn = 'received';
        
        $query = $this->_user->getInboxQuery();
        
        $urlCallback = array($this, 'gridUrlCallback');
        $viewScript = 'message/_inboxGrid.phtml';
            
        $this->view->grid = new App_View_Control_Grid(compact(
            'itemsPerPage', 'currentPage', 'sortBy', 
            'sortableColumns', 'defaultColumn',
            'query', 'urlCallback', 
            'viewScript', 'viewVars'
        ));
    }
    
    public function sentAction()
    {
        $request = $this->getRequest();
        
        $itemsPerPage = $request->getParam('items');
        $currentPage = $request->getParam('page');
        $sortBy = $request->getParam('sort');
        
        $sortableColumns = array(
            'to' => array('t.last_name', 't.first_name'),
            'subject' => 'm.subject',
            'sent' => 'm.sent_date'
        );
        $defaultColumn = 'sent';
        
        $query = $this->_user->getSentQuery();
            
        $urlCallback = array($this, 'gridUrlCallback');
        $viewScript = 'message/_sentGrid.phtml';
            
        $this->view->grid = new App_View_Control_Grid(compact(
            'itemsPerPage', 'currentPage', 'sortBy', 
            'sortableColumns', 'defaultColumn',
            'query', 'urlCallback', 
            'viewScript', 'viewVars'
        ));
    }
    
    public function deletedAction()
    {
        $request = $this->getRequest();
        
        $itemsPerPage = $request->getParam('items');
        $currentPage = $request->getParam('page');
        $sortBy = $request->getParam('sort');
        
        $sortableColumns = array(
            'from' => array('f.last_name', 'f.first_name'),
            'subject' => 'm.subject',
            'received' => 'm.sent_date'
        );
        $defaultColumn = 'received';
        
        $query = $this->_user->getDeletedQuery();
            
        $urlCallback = array($this, 'gridUrlCallback');
        $viewScript = 'message/_deletedGrid.phtml';
            
        $this->view->grid = new App_View_Control_Grid(compact(
            'itemsPerPage', 'currentPage', 'sortBy', 
            'sortableColumns', 'defaultColumn',
            'query', 'urlCallback', 
            'viewScript', 'viewVars'
        ));
    }
    
    public function composeAction()
    {
        $request = $this->getRequest();        
        
        if($id = $request->getParam('id')){
            if(empty($id) || !$message = Model_MessageTable::getInstance()->findDraft($id, $this->_user)){
                throw new App_Exception('Attempt to edit message with id:' . $id);
            }
            $this->view->message = $message;
        }else{
            $message = new Model_Message();
            $message->From = $this->_user;
            
            $userId = $request->getParam('user');
            if(!empty($userId) && $user = Model_UserTable::getInstance()->findOneById($userId)){
                $message->To[] = $user;
            }
        }
        
        // Don't really want to put this here but tinyMCE is messing with me colorbox
        $this->view->headScript()->appendFile($this->view->baseUrl('js/jquery.colorbox-min.js'))
            ->appendFile($this->view->baseUrl('js/colorbox-setup.js'));
        
        $form = new Account_Form_Message($message);
        
        $autocompleteUrl = $this->_helper->url->url(array('action' => 'recipient'), 'messages');
        $form->getElement('to')->setAttrib('src', $autocompleteUrl);
        
        if($request->isPost() && $form->isValidWithDoctrineRecord($request->getPost(), $message)){
            
            if($form->send->isChecked()){
                
                $message->send();
                
                $this->_helper->flashMessenger->addMessage('Message sent');
                $this->_helper->redirector->gotoRoute(array('action' => 'inbox'), 'messages');
                
            }/*else{
                
                // done this way to allow saving of drafts but at the moment fairly useless.
                $message->save();
                if($id !== $message->id){
                    $this->_helper->redirector->gotoRoute(array(
                        'action' => 'compose', 'id' => $message->id
                    ), 'message');
                }
                
            }*/
            
            $this->view->message = $message;            
        }
        
        $this->view->form = $form;
    }
    
    public function recipientAction()
    {
        $name = $this->getRequest()->getParam('id');
        
        $data = array();
        
        if(strlen($name) > 1){
            $users = Model_UserTable::getInstance()->findUsersByPartialNameAndGroups($name, $this->_user->Groups);
        
            foreach($users as $user){
                $data[] = $user->fullName;
            }
        }
        
        $this->_helper->layout->setLayout('none');
        $this->view->data = $data;
    }
    
    public function viewAction()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id');
        
        if(empty($id) || !$message = Model_MessageTable::getInstance()->findByIdAndUser($id, $this->_user)){
            throw new App_Exception('Attempt to edit message with id:' . $id);
        }
        
        if($message->From->id != $this->_user->id){
            $this->view->toUser = $this->_user;
        }
        
        if($message->type == 'request' && $request->getParam('request') == 'approve'){
            
            $message->From->approveForGroup($message->subject);
            $message->setDeletedForAll();
            
            $this->_helper->flashMessenger->addMessage('Membership request approved');
            $this->_helper->redirector->gotoRoute(array('action' => 'inbox'), 'messages');
        }
        
        $this->view->message = $message;
    }
    
    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        if(
            empty($id) || 
            !Model_MessageTable::getInstance()->setStatusByIdAndRecipient('deleted', $id, $this->_user)
        ){
            throw new App_Exception('Attempt to delete message with id:' . $id);
        }
        
        $this->_helper->flashMessenger->addMessage('Message deleted');
        $this->_helper->redirector->gotoRoute(array('action' => 'inbox'), 'messages');
    }
    
    public function restoreAction()
    {
        $id = $this->getRequest()->getParam('id');
        if(
            empty($id) || 
            !Model_MessageTable::getInstance()->setStatusByIdAndRecipient('read', $id, $this->_user)
        ){
            throw new App_Exception('Attempt to restore message with id:' . $id);
        }
        
        $this->_helper->flashMessenger->addMessage('Message restored');
        $this->_helper->redirector->gotoRoute(array('action' => 'inbox'), 'messages');
    }
    
    public function navigationAction()
    {
        $nav = new Zend_Navigation();
        $actions = new Zend_Navigation_Page_Mvc(array('label' => 'Actions'));
        $nav->addPage($actions);
        
        $actions->addPage(array(
            'module' => 'account',
            'controller' => 'message',
            'action' => 'compose', 
            'route' => 'messages',
            'label' => 'Compose message',
            'title' => 'Compose and send a new message'
        ));
            
        $mailBoxes = new Zend_Navigation_Page_Mvc(array('label' => 'Mail boxes'));
        $nav->addPage($mailBoxes);
        
        $newCount = $this->_user->getNewMessageCount();
        $inboxLabel = ($newCount > 0) ? 'Inbox (' . $newCount . ')' : 'Inbox';
        
        $mailBoxes->addPage(array(
            'module' => 'account',
            'controller' => 'message',
            'action' => 'inbox', 
            'route' => 'messages',
            'label' => $inboxLabel,
            'title' => 'See messages sent to you'
        ));
        
        $mailBoxes->addPage(array(
            'module' => 'account',
            'controller' => 'message',
            'action' => 'sent', 
            'route' => 'messages',
            'label' => 'Sent messages',
            'title' => 'See messages sent from you'
        ));
        
        $mailBoxes->addPage(array(
            'module' => 'account',
            'controller' => 'message',
            'action' => 'deleted', 
            'route' => 'messages',
            'label' => 'Deleted messages',
            'title' => 'See messages deleted from your inbox'
        ));
        
        $this->view->nav = $nav;
    }
    
    public function gridUrlCallback($page, $items, $sort)
    {
        return $this->_helper->url->url(
            array('action' => $this->getRequest()->getParam('action')) + compact('page', 'items', 'sort'), 
            'messages', true, false
        );
    }
}