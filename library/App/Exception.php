<?php
class App_Exception extends Exception {
    
    public function __construct($message = '', $code = 0){
        
        parent::__construct($message, $code);
        trigger_error($message);
    }
    
    
}
?>