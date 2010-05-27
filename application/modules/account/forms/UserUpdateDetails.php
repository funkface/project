<?php

class Account_Form_UserUpdateDetails extends Account_Form_User
{
    public function init()
    {
        $this->_addAllElementsToForm();
        $this->removeElement('username');
    }
}