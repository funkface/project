<?php
class App_View_Helper_DisplayUploadedImage extends Zend_View_Helper_Abstract
{
    public function displayUploadedImage($filename, $version = '')
    {
        
        $version = empty($version) ? '' : $version . '_';
        
        if(!empty($filename)){
            
            $filename = $version . $filename;
            
            if(file_exists(
                Zend_Registry::get('config')->filepath->upload->image . DIRECTORY_SEPARATOR . $filename
            )){
                $src = Zend_Registry::get('config')->webpath->upload->image . '/' . $filename;
            }else{
                $filename = '';
            }
        }
        
        if(empty($filename)){
            
            $src = 'img/' .  $version . 'no_image.gif';
        }
        
        $src = $this->view->baseUrl($src);
        $src = $this->view->escape($src);
        
        return $src;
    }
}