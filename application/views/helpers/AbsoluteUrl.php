<?php
class App_View_Helper_AbsoluteUrl extends Zend_View_Helper_Abstract
{

    public function absoluteUrl($file = null, $baseUrlAlreadyAdded = false){
    
        $url = $baseUrlAlreadyAdded ? $file : $this->view->baseUrl($file);
        
        if(strpos($url, '://') === false){
            
            $url = 'http://' . $_SERVER["SERVER_NAME"] . '/' . ltrim($url, '/\\');
        }
        
        return $url;
    }
    
}