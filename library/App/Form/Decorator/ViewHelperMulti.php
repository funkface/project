<?php
class App_Form_Decorator_ViewHelperMulti extends Zend_Form_Decorator_ViewHelper
{
    public function getValue($element)
    {
        $value = parent::getValue($element);
        
        if(is_array($value)){
            $value = implode("\n", $value);
        }
        
        return $value;
    }
}