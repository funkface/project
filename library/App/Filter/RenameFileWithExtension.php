<?php

class App_Filter_RenameFileWithExtension implements Zend_Filter_Interface
{
    public function filter($value)
    {
        $source = $value;
        $fileinfo = pathinfo($source);
        $filename = uniqid() . '.' . $fileinfo['extension'];
        $destination = $fileinfo['dirname'] . DIRECTORY_SEPARATOR . $filename;

        $renameFilter = new Zend_Filter_File_Rename($destination, null, true);
        $renameFilter->filter($source);

        return $destination;
    }
}