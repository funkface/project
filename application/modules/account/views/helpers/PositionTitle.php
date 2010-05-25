<?php

class Zend_View_Helper_PositionTitle
{
    function positionTitle($template)
    {
        if ($template->title) {
            return $template->title;
        }
        return $template->name;
    }
}