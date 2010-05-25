<?php

class Zend_View_Helper_BlockTitle
{
    function blockTitle($block, $title=false)
    {
        if ($title) {
            return $title;
        }
        if ($block->type_title) {
            return $block->type_title;
        }
        return $block->type_name;
    }
}