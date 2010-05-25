<?php
class App_Filter_CanonicaliseFileName implements Zend_Filter_Interface
{
    public function filter($value)
    {
        $fileinfo = pathinfo($value);

        $filename = preg_replace(
            array('/[^\w\s\-_\.]+|^[\s\-_\.]+|[\s\-_\.]+$/', '/[\s\-_]+/', '/\.+/'),
            array('', '-', '.'),
            $fileinfo['basename']
        );

        $destination = $fileinfo['dirname'] . DIRECTORY_SEPARATOR . $filename;

        // (have to set overwrite to true or this just returns null)
        $renameFilter = new Zend_Filter_File_Rename(array('target' => $destination, 'overwrite' => true));
        $renameFilter->filter($value);

        return $destination;
    }
}

?>