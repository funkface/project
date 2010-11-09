<?php

class Forum_IndexController extends Zend_Controller_Action
{
    protected $_user;

    public function init()
    {
        if(!$this->view->group){
            
            $abbr = $this->getRequest()->getParam('abbr');
            $this->view->group = Model_GroupTable::getInstance()->findByAbbr($abbr);
    
            if(!$this->view->group){
                throw new Zend_Controller_Router_Exception('No such group as ' . $abbr, 404);
            }
        }
        
        // use this method to ensure role title is added to post user details
        $user = Zend_Auth::getInstance()->getIdentity();
        $this->_user = Model_UserTable::getInstance()->findOneByIdWithRoleForGroup($user->id, $this->view->group);
        
        //$this->_user = Zend_Registry::get('user');

        if($this->getRequest()->getActionName() != 'request' && !$this->_user->isMemberOfGroup($this->view->group)){
            $this->_helper->redirector->gotoRoute(array(
                'action' => 'request', 
                'abbr' => $this->view->group->abbr
            ), 'forum');
        }

        $this->view->messages = $this->_helper->flashMessenger->getMessages();
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        
        $itemsPerPage = $request->getParam('items');
        $currentPage = $request->getParam('page');
        $sortBy = $request->getParam('sort');
        
        $sortableColumns = array(
            'important' => array('t.sticky', 't.created_date'),
            'topic' => 't.created_date',
            'posts' => 't.num_posts',
            'last' => 't.last_date'
        );
        $defaultColumn = '-important';
        
        $query = Model_TopicTable::getInstance()->getForumQuery($this->view->group);
        
        $urlCallback = array($this, 'gridUrlCallback');
        $viewScript = 'index/_forumGrid.phtml';
        $viewVars = array('group' => $this->view->group);
            
        $this->view->grid = new App_View_Control_Grid(compact(
            'itemsPerPage', 'currentPage', 'sortBy', 
            'sortableColumns', 'defaultColumn',
            'query', 'urlCallback', 
            'viewScript', 'viewVars'
        ));
    }
    
    public function topicAction()
    {
        $request = $this->getRequest();
        $topicId = $request->getParam('id');
        
        $this->view->topic = Model_TopicTable::getInstance()
            ->findOneByIdAndGroup($topicId, $this->view->group);
            
        if(!$this->view->topic){
            throw new Zend_Controller_Router_Exception('No such topic as ' . $topicId, 404);
        }
            
        $query = Model_PostTable::getInstance()->getTopicPostsQuery(
            $this->view->topic, $this->view->group, $this->_user
        );
        
        $itemsPerPage = $request->getParam('items');
        $currentPage = $request->getParam('page');
        $sortBy = $request->getParam('sort');
        
        $sortableColumns = array('posted' => 'p.created_date');
        $defaultColumn = 'posted';
        
        $urlCallback = array($this, 'gridUrlCallback');
        $viewScript = 'index/_topicGrid.phtml';
        $viewVars = array('group' => $this->view->group, 'topic' => $this->view->topic);
            
        $this->view->grid = new App_View_Control_Grid(compact(
            'itemsPerPage', 'currentPage', 'sortBy', 
            'sortableColumns', 'defaultColumn',
            'query', 'urlCallback', 
            'viewScript', 'viewVars'
        ));
    }
    
    public function newAction()
    {
        $post = new Model_Post();
        $post->User = $this->_user;
        $post->Topic->Group = $this->view->group;
        $post->Topic->User = $this->_user;
        
        $this->_editPost($post);
    }
    
    public function replyAction()
    {
        $topicId = $this->getRequest()->getParam('topic');
        
        if(
            empty($topicId) || 
            !$this->view->topic  = Model_TopicTable::getInstance()->findOneByIdAndGroup($topicId, $this->view->group)
        ){
            throw new Zend_Controller_Router_Exception('No topic provided', 404);
        }
        
        $post = new Model_Post();
        $post->User = $this->_user;
        $post->Topic = $this->view->topic;

        $this->_editPost($post);  
    }
    
    public function editAction()
    {
        $request = $this->getRequest();  
        $topicId = $request->getParam('topic');
        $id = $request->getParam('post');

        if(
            empty($topicId) || 
            !$this->view->topic = Model_TopicTable::getInstance()->findOneByIdAndGroup($topicId, $this->view->group)
        ){
            throw new Zend_Controller_Router_Exception('No topic provided', 404);
        }
        
        if(
            empty($id) ||
            !$post = Model_PostTable::getInstance()->findDraft(
                $id, $this->_user, $this->view->group, $this->view->topic
            )
        ){
            throw new Zend_Controller_Router_Exception('Attempt to edit post with id:' . $id, 404);
        }
        
        $this->_editPost($post);
    }
    
    protected function _editPost($post)
    {
        $request = $this->getRequest();
             
        // Don't really want to put this here but tinyMCE is messing with me colorbox
        $this->view->headScript()->appendFile($this->view->baseUrl('js/jquery.colorbox-min.js'))
            ->appendFile($this->view->baseUrl('js/colorbox-setup.js'));
        
        $this->view->form = new Forum_Form_Post($post);
        $this->view->post = $post;
        
        if(
            $request->isPost() && (
                !$post->isFirst() || 
                $this->view->form->isValidWithDoctrineRecord($request->getPost(), $post->Topic)
            ) && $this->view->form->isValidWithDoctrineRecord($request->getPost(), $post)
        ){
            
            if($this->view->form->save->isChecked()){
                
                $post->post();
                
                $this->_helper->flashMessenger->addMessage('Post saved');
                $this->_helper->redirector->gotoRoute(array(
                    'action' => 'topic', 
                    'id' => $post->Topic->id,
                    'abbr' => $this->view->group->abbr
                ), 'forumDetail');
                
            }else{

                // Really no need to save previews until there's a way to recover drafts, if ever.
                /*
                $this->view->post->save();
                
                if($id !== $this->view->post->id){
                    $this->_helper->redirector->gotoRoute(array(
                        'action' => 'edit', 
                        'post' => $this->view->post->id, 
                        'topic' => $this->view->post->Topic->id, 
                        'abbr' => $this->view->group->abbr
                    ), 'topicReply');
                } 
                */
                
                $this->view->preview = true;
            }
        }
    }
    
    public function deleteAction()
    {
        $request = $this->getRequest();       
        $topicId = $request->getParam('topic');
        $id = $request->getParam('post');
        
        if(
            empty($topicId) || 
            !$topic = Model_TopicTable::getInstance()->findOneByIdAndGroup($topicId, $this->view->group)
        ){
            throw new Zend_Controller_Router_Exception('No topic provided', 404);
        }
        
        if(
            empty($id) || 
            !$post = Model_PostTable::getInstance()->findDraft($id, $this->_user, $this->view->group, $topic)
        ){
            throw new Zend_Controller_Router_Exception('Attempt to delete post with id:' . $id, 404);
        }
        
        if(!$post->usercanEdit){
            throw new Zend_Controller_Router_Exception('Attempt to delete post with id:' . $id, 404);
        }
        
        $post->softDelete();
        $this->_helper->flashMessenger->addMessage('Post deleted');
        
        if($post->isFirst() && $post->isLast()){
            
            $topic->softDelete();
            $this->_helper->flashMessenger->addMessage('Topic deleted');
            
            $this->_helper->redirector->gotoRoute(array(
                'action' => 'index',
                'abbr' => $this->view->group->abbr
            ), 'forum');
            
        }else{
            
            $this->_helper->redirector->gotoRoute(array(
                'action' => 'topic',
                'id' => $topic->id,
                'abbr' => $this->view->group->abbr
            ), 'forumDetail');
        }

    }
    
    public function deleteTopicAction()
    {
        $topicId = $this->getRequest()->getParam('topic');
        
        if(
            empty($topicId) || 
            !$topic = Model_TopicTable::getInstance()->findOneByIdAndGroup($topicId, $this->view->group)
        ){
            throw new Zend_Controller_Router_Exception('No topic provided', 404);
        }
        
        if(!$this->_user->isLeaderOfGroup($this->view->group)){
            throw new Zend_Controller_Router_Exception('Attempt to delete topic with id:' . $topicId, 404);
        }
        
        $topic->softDelete();
        
        $this->_helper->flashMessenger->addMessage('Topic deleted');
        
        $this->_helper->redirector->gotoRoute(array(
            'action' => 'index',
            'abbr' => $this->view->group->abbr
        ), 'forum');
    }
    
    public function stickAction($sticky = true)
    { 
        $topicId = $this->getRequest()->getParam('topic');
        
        if(
            empty($topicId) || 
            !$topic = Model_TopicTable::getInstance()->findOneByIdAndGroup($topicId, $this->view->group)
        ){
            throw new Zend_Controller_Router_Exception('No topic provided', 404);
        }
        
        if(!$this->_user->isLeaderOfGroup($this->view->group)){
            throw new Zend_Controller_Router_Exception('Attempt to stick topic with id:' . $topicId, 404);
        }
        
        $topic->sticky = $sticky;
        $topic->save();
        
        $this->_helper->flashMessenger->addMessage($sticky ? 'Topic stuck' : 'Topic unstuck');
        
        $this->_helper->redirector->gotoRoute(array(
            'action' => 'index',
            'abbr' => $this->view->group->abbr
        ), 'forum');
    }
    
    public function unstickAction()
    {
        $this->stickAction(false);
    }
    
    public function requestAction()
    {
        $role = $this->_user->getRoleForGroup($this->view->group);
        
        switch($role){
            
            case 'member':
            case 'leader':
                
                $this->_helper->redirector->gotoRoute(array('action' => 'index'), 'forum');
                break;
                
            case 'request':
                
                $this->view->pending = true;
                break; 
                
            default:
                
                if($this->getRequest()->getParam('id') == 'join'){
                    $this->_user->requestMembership($this->view->group);
                    $this->_helper->redirector->gotoRoute(array(
                        'action' => 'request', 'abbr' => $this->view->group->abbr
                    ), 'forum');
                }
        }
    }
    
    public function navigationAction()
    {
        $nav = new Zend_Navigation();
        $actions = new Zend_Navigation_Page_Mvc(array('label' => 'Forum actions'));
        $nav->addPage($actions);
        
        $actions->addPage(array(
            'module' => 'forum',
            'controller' => 'index',
            'action' => 'new', 
            'params' => array('abbr' => $this->view->group->abbr),
            'route' => 'topicNew',
            'label' => 'New Topic',
            'title' => 'Start a new topic'
        ));
            
        if($this->view->topic){
            
            $topicActions = new Zend_Navigation_Page_Mvc(array('label' => 'Topic actions'));
            $nav->addPage($topicActions);
    
            $topicActions->addPage(array(
                'module' => 'forum',
                'controller' => 'index',
                'action' => 'reply', 
                'params' => array(
                    'abbr' => $this->view->group->abbr,
                    'topic' => $this->view->topic->id
                ),
                'route' => 'topicReply',
                'label' => 'Post reply',
                'title' => 'Post a reply to this topic'
            ));
            
            if($this->_user->isLeaderOfGroup($this->view->group)){
                
                $topicActions->addPage(array(
                    'module' => 'forum',
                    'controller' => 'index',
                    'action' => 'delete-topic', 
                    'params' => array(
                        'abbr' => $this->view->group->abbr,
                        'topic' => $this->view->topic->id
                    ),
                    'route' => 'topicReply',
                    'label' => 'Delete topic',
                    'title' => 'Delete this entire topic',
                    'confirm' => true
                ));
                
                $topicActions->addPage(array(
                    'module' => 'forum',
                    'controller' => 'index',
                    'action' => $this->view->topic->sticky ? 'unstick' : 'stick', 
                    'params' => array(
                        'abbr' => $this->view->group->abbr,
                        'topic' => $this->view->topic->id
                    ),
                    'route' => 'topicReply',
                    'label' => $this->view->topic->sticky ? 'Unstick topic' : 'Stick topic',
                    'title' => $this->view->topic->sticky ? 'Remove sticky status from this topic' : 
                        'Make this topic appear at the top of the topic list'
                ));
                
            }
        }
        
        $this->view->nav = $nav;
    }
    
    public function gridUrlCallback($page, $items, $sort)
    {
        return $this->_helper->url->url(compact('page', 'items', 'sort'), null, false, false);
    }

}

