<?php
class App_View_Helper_EscapeInlineJsString extends Zend_View_Helper_Abstract {

    public function escapeInlineJsString($value){
    
        $filter = new App_Filter_EscapeInlineJsString($this->view->getEncoding());
        return $filter->filter($value);
    }
    
}