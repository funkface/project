<?php
class App_Filter_TruncateHtmlText extends Zend_Filter_StripTags
{
    
    protected $length;
    
    public function __construct($length = 127)
    {
        $this->length = $length;
    }
    
    public function filter($value)
    {
        $value = parent::filter($value);
        $value = html_entity_decode($value, ENT_COMPAT, 'UTF-8');
        $value = preg_replace('@\s+@', ' ', $value); // collapse whitespace
        
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
