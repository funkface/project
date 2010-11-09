<?php
class App_View_Helper_GroupAbbr extends Zend_View_Helper_Abstract
{

    public function groupAbbr(Model_Group $group)
    {
        return '<abbr title="' . $this->view->escape($group->title) . '">' . strtoupper($group->abbr) . '</abbr>';
    }
}