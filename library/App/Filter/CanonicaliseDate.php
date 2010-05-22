<?php
class App_Filter_CanonicaliseDate implements Zend_Filter_Interface
{
    
    public function filter($value)
    {
        $date = preg_replace('@([0-9]{1,2})/+([0-9]{1,2})/+([0-9]{0,4})@', '$2/$1/$3', $value);
        $date = strtotime($date);
        
        if($date === false){
            return $value;
        }
        
        $date = date('Y-m-d H:i:s', $date);
        
        return $date;
    }

}
?>