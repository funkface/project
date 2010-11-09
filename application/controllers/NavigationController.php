<?php

class NavigationController extends Zend_Controller_Action
{
    protected static $nav;
    
    public function init()
    {
        if(!self::$nav){
            
            $nav = new Zend_Navigation();
            
            $nav->addPage(array(
                'controller' => 'index', 
                'action' => 'index', 
                'route' => 'default',
                'label' => 'Home',
                'title' => 'Home page'
            ));
            
            $nav->addPage(array(
                'controller' => 'contact',
                'action' => 'index',
                'route' => 'default',
                'label' => 'Contact',
                'title' => 'Contact page',
                'class' => 'last'
            ));

        
            self::$nav = $nav;
        }

    }
    
    public function mainAction()
    {
        
        $this->view->nav = self::$nav;
        
        foreach($this->view->nav as $item){
            $class = $item->class;
            if($item->isActive()){
                $class .= ' active';
            }
            $item->class = trim($class);
        }
    }
    
    public function groupAction()
    {
        $group = self::$nav->findOneById('current-group');
        $this->view->nav = $group->getPages();
    }
    
    public function accountAction()
    {
        $nav = new Zend_Navigation();
            
        $nav->addPage(array(
            'module' => 'account',
            'controller' => 'index', 
            'action' => 'index', 
            'route' => 'account',
            'label' => 'My account',
            'title' => 'Account main page',
            'class' => 'first'
        ));
        
        $nav->addPage(array(
            'module' => 'account',
            'controller' => 'settings', 
            'action' => 'details', 
            'route' => 'account',
            'label' => 'My details',
            'title' => 'Edit your details'
        ));
        
        $nav->addPage(array(
            'module' => 'account',
            'controller' => 'settings', 
            'action' => 'password', 
            'route' => 'account',
            'label' => 'Change password',
            'title' => 'Reset your password'
        ));
        
        $nav->addPage(array(
            'module' => 'account',
            'controller' => 'settings', 
            'action' => 'personality', 
            'route' => 'account',
            'label' => 'Personality settings',
            'title' => 'Edit your picture and signature'
        ));
        
        $nav->addPage(array(
            'module' => 'account',
            'controller' => 'message', 
            'action' => 'inbox', 
            'route' => 'messages',
            'label' => 'My messages',
            'title' => 'View and send messages'
        ));
        
        $this->view->nav = $nav;
    }
    
    public function footerAction()
    {
        $nav = new Zend_Navigation();
            
        $nav->addPage(array(
            'module' => 'default',
            'controller' => 'index', 
            'action' => 'index', 
            'route' => 'default',
            'label' => 'Home Page',
            'title' => 'Home Page',
            'class' => 'first'
        ));
        
        $nav->addPage(array(
            'module' => 'default',
            'controller' => 'legal', 
            'action' => 'privacy', 
            'route' => 'default',
            'label' => 'Privacy Policy',
            'title' => 'Privacy Policy'
        ));
        
        $nav->addPage(array(
            'module' => 'default',
            'controller' => 'legal', 
            'action' => 'terms',
            'route' => 'default',
            'label' => 'Terms & Conditions',
            'title' => 'Terms & Conditions'
        ));
        
        $nav->addPage(array(
            'module' => 'default',
            'controller' => 'contact', 
            'action' => 'index', 
            'route' => 'default',
            'label' => 'Contact Us',
            'title' => 'Contact Us'
        ));
        
        $this->view->nav = $nav;
    }
    
}

