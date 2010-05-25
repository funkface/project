<?php

class Zend_View_Helper_LoggedInMessage
{
    function loggedInMessage()
    {
        if (false == Zend_Auth::getInstance()->hasIdentity()) {
            return false;
        }

        $user = Zend_Auth::getInstance()->getIdentity();

        return '<a href="/admin/user/edit/id/'.$user->id.'" title="Edit your account">Welcome '.$user->username .'</a>';
    }
}