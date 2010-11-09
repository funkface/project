<?php
class App_Filter_AddValueToDoctrineCollection implements Zend_Filter_Interface
{
    protected $_collection;
    protected $_field;
    
    public function __construct($array, $field = false)
    {
        if(is_array($array)){
            $collection = $array[0];
            $field = $array[1];
        }else{
            $collection = $array;
        }
        
        if(!$collection instanceof Doctrine_Collection){
            throw new App_Exception('First argument must be Doctrine_Collection');
        }
        
        $this->_collection = $collection;
        $this->_field = (string)$field;
    }
    
    public function filter($value)
    {
        $this->_collection[]->{$this->_field} = basename($value);
        return $value;
    }
}