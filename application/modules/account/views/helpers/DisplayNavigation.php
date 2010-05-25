<?php

class Zend_View_Helper_DisplayNavigation
{
    protected $_view;

    public function setView($view)
    {
        $this->_view = $view;
    }

    function displayNavigation($container)
    {
                $pages = $this->_view->navigation();
                $admin = $pages->findOneByLabel('Admin');
                $pages->removePage($admin);

                switch ($container) {
                    case 'pages':
                        return $pages;
                        break;
                    case 'admin':
                        return $this->_view->navigation()->menu()->renderMenu($admin, array('ulClass' => 'admin'));
                        break;
                }
                throw new Exception('Invalid container');
    }
}