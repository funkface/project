<?php
class App_Filter_TruncatePhpbbPostText implements Zend_Filter_Interface
{
    
    protected $length;
    
    public function __construct($length = 127)
    {
        $this->length = $length;
    }
    
    public function filter($value)
    {
        $value = html_entity_decode($value);
        $value = preg_replace(
            array(
                '@\s+@', // collapse whitespace
                '@\[(img|attachment).*\].*\[/\1.*\]|\[.+\]@Us' // remove bbcode
            ), array(
                ' ',
                ''
            ), $value);
        
        $length = strlen($value);

        if($length > $this->length){
            $pos = strrpos($value, ' ', -($length - $this->length) - 1);
        }

        if($pos){
            $value = substr($value, 0, $pos);
        }else if($length > $this->length){
            $value = substr($value, 0, $this->length);
        }

        return $value; 
    }

}
?>
