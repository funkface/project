<?php
class App_View_Helper_DisplayRecipients extends Zend_View_Helper_Abstract
{

    public function displayRecipients(Doctrine_Collection $recipients){
    
        $output = array();
        foreach($recipients as $recipient){
            $output[] = $this->view->escape($recipient->first_name . ' ' . $recipient->last_name);
        }
        
        return implode(', ', $output);
    }
    
}