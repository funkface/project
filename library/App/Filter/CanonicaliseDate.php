<?php
class App_Filter_CanonicaliseDate implements Zend_Filter_Interface
{
    protected $dateFormat = 'Y-m-d H:i:s';
    
    public function __construct($dateFormat)
    {
        if(!empty($dateFormat)){
            $this->dateFormat = $dateFormat;
        }
    }
    
    public function filter($value)
    {
        $date = preg_replace('@([0-9]{1,2})/+([0-9]{1,2})/+([0-9]{0,4})@', '$2/$1/$3', $value);
        $date = strtotime($date);
        
        if($date === false){
            return $value;
        }
        
        $date = date($this->dateFormat, $date);
        
        return $date;
    }

}
?>