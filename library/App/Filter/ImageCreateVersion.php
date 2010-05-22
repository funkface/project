<?php

class App_Filter_ImageCreateVersion implements Zend_Filter_Interface
{
    protected $_versions;

    public function __construct($options)
    {
        $this->_versions = $options;
    }

    public function filter($value)
    {
        $source = $value;
        $fileinfo = pathinfo($source);

        foreach ($this->_versions as $versionKey => $options) {

            $destination = $fileinfo['dirname'] . DIRECTORY_SEPARATOR . $versionKey . '_' . $fileinfo['filename'] . '.' . $fileinfo['extension'];
            $method = isset($options['method']) ? $options['method'] : array('limit', 'crop');
            $format = isset($options['format']) ? $options['format'] : null;
            App_Image::resizeImage(
                $source,
                $destination,
                $options['width'], $options['height'], $method, $format);
        }

        return $value;
    }
}