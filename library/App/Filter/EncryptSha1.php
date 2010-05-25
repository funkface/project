<?php

class App_Filter_EncryptSha1 implements Zend_Filter_Interface
{
    public function filter($value)
    {
        if (empty($value)) {
            return null;
        }

        $config = Zend_Registry::get('config');

        $salt = $config->auth->salt;
        $value = sha1($salt.$value);

        return strtolower((string) $value);
    }
}