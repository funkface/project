<?php
class App_View_Helper_FormElementErrors extends Zend_View_Helper_Abstract
{
    public function formElementErrors($errors, array $options = null)
    {
        $output = '';
        
        if(is_array($errors)){
            $output .= '<ul class="errors">';
            $first = true;
            foreach($errors as $m => $message){
                $output .= '<li';
                if($first){
                    $first = false;
                    $output .= ' class="first"';
                }
                $output .= '>' . $this->view->escape($message) . '</li>';   
            }
            $output .= '</ul>';
        }
        
        return $output;
    }
}