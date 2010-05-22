<?php

class NavigationController extends Zend_Controller_Action
{
    protected static $nav;
    
    public function init()
    {
        if(!self::$nav){

            $nav = new Zend_Navigation();
        
            self::$nav = $nav;
        }

    }
    
    public function mainAction()
    {
        $this->view->nav = self::$nav;
    }
    
    public function groupAction()
    {
    	/*
        $group = self::$nav->findOneById('current-group');
        $this->view->nav = $group->getPages();
		*/
    }

}

