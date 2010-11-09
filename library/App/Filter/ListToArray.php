<?php
class App_Filter_ListToArray implements Zend_Filter_Interface
{
    
    public function filter($value)
    {
        if(is_string($value)){
            $value = preg_split('/\s*([\n\r,;]\s*)+/', $value, 0, PREG_SPLIT_NO_EMPTY);
        }
        
        $value = (array)$value;
        foreach($value as &$row){
            $row = preg_replace('/\s+/', ' ', (string)$row);
        }
        
        return $value;
    }

}
?>