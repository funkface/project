<?php
class App_View_Helper_AbsoluteUrl extends Zend_View_Helper_Abstract
{

    public function absoluteUrl(array $urlOptions = array(), $name = null, $reset = false, $encode = true){
    
        $url = $this->view->url($urlOptions, $name, $reset, $encode);
        $url = 'http://' . $_SERVER["SERVER_NAME"] . $url;
        
        return $url;
    }
    
}