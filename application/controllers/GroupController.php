<?php

class GroupController extends Zend_Controller_Action
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
    }

    public function indexAction()
    {
        $this->view->caseStudies = Model_CaseStudyTable::getInstance()->findRecent(3, $this->view->group->id);
        $this->view->posts = Model_PostTable::getInstance()->findRecent(4, $this->view->group);
    }
    
    public function caseStudiesAction()
    {
        $csTable = Model_CaseStudyTable::getInstance();
        $this->view->caseStudies = Model_CaseStudyTable::getInstance()->findRecent(0, $this->view->group->id);
        
        $id = $this->getRequest()->getParam('id');
        if(!$id || (!$this->view->caseStudy = $csTable->findOneById($id))){
            $this->view->caseStudy = $csTable->findFeatured($this->view->group->id);
        } 
    }
    
    public function eventsAction()
    {
        $this->view->eventId = $this->getRequest()->getParam('id');
    }
    
    public function galleryAction()
    {
        
    }

}

