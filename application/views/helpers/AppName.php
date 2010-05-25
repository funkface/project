<?php
class App_View_Helper_AppName extends Zend_View_Helper_Abstract
{
    
    public function appName($escape = true)
    {
        $name = Zend_Registry::get('config')->app->name;
        if($escape){
            $name = $this->view->escape($name);
        }
        return $name;
    }
    
}
?>