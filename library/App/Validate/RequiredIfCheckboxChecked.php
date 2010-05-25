<?php
class App_Validate_RequiredIfCheckboxChecked extends Zend_Validate_NotEmpty
{
    
    protected $_checkbox;    
    
    public function __construct(Zend_Form_Element_Checkbox $checkbox){
        
        $this->_checkbox = $checkbox;
    }
    
    public function isValid($value)
    {
        if(!$this->_checkbox->isChecked()){
            return true;
        }
        
        return parent::isValid($value);    
    }
    
}
?>