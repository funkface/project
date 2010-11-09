<?php
class App_View_Helper_pageTitle extends Zend_View_Helper_Abstract
{
    protected $_title;
    protected $_order;
    
    public function __construct()
    {
        $this->_order = Zend_Registry::get('config')->title->order;
    }
    
    public function pageTitle($title)
    {
        $this->_title = $title;
        $this->view->headTitle($title, $this->_order);
        
        return $this;
    }
    
    public function __toString()
    {
        return $this->view->escape($this->_title);
    }
}