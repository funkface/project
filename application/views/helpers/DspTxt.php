<?php
class App_View_Helper_DspTxt extends Zend_View_Helper_Abstract
{

    public function dspTxt($txt)
    {
        $txt = $this->view->escape($txt);
        $paras = preg_split('@(\r?\n){2,}@', $txt);
        $txt = '';

        foreach($paras as $p){
            $txt .= '<p>' . nl2br($p) . "</p>\n";
        }

        return $txt;
    }
}