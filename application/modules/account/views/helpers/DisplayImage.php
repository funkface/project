<?php

class Zend_View_Helper_DisplayImage
{
    function displayImage($filename, $version='thumb')
    {
        switch ($version) {
            case 'thumb':
                if (substr($filename, 0, 6) != 'thumb_') {
                    $filename = 'thumb_' . $filename;
                }
                break;
        }

        if (!file_exists(Zend_Registry::get('config')->filepath->upload->image . DIRECTORY_SEPARATOR . $filename)) {
            return '';
        }

        return '<img src="' . Zend_Registry::get('config')->webpath->upload->image . '/' .$filename .'">';
    }
}