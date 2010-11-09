<?php
class App_View_Helper_Messages extends Zend_View_Helper_Abstract
{
    public function messages()
    {
        $output = '';
        
        if($this->view->messages){
            $output .= '<ul id="messages">';
            foreach($this->view->messages as $m => $message){
                $output .= '<li';
                if($m == 0){
                    $output .= ' class="first"';
                }
                $output .= '>' . $this->view->escape($message) . '</li>';   
            }
            $output .= '</ul>';
        }
        
        return $output;
    }
    
}