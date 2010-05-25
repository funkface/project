<?php

class App_Validate_Password extends Zend_Validate_Abstract
{
    const INSECURE = 'passwordInsecure';

    protected $_minChars = 8;
    protected $_user;

    /**
     * Override invalid message
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INSECURE => 'Insecure password (must be at least 8 chars with at least 1 number and not based on your own details)'
    );

    public function __construct($user=false)
    {
        $this->_user = $user;
    }

    public function isValid($value)
    {
        $invalidStrings = array('password');

        if ($this->_user) {
            $invalidStrings[] = $this->_user['fullname'];
            $invalidStrings[] = $this->_user['username'];
        }

        $containsInvalidString = false;
        foreach ($invalidStrings as $invalidString) {
            if (stripos($value, $invalidString)!==false) {
                $containsInvalidString = true;
                break;
            }

        }


        $validatorChain = new Zend_Validate();
        $validatorChain->addValidator(new Zend_Validate_StringLength($this->_minChars, 99))
                       ->addValidator(new Zend_Validate_Regex('/[0-9]/'))
                       ->addValidator(new Zend_Validate_Regex('/[a-z]/i'));



        if (false==$containsInvalidString && $validatorChain->isValid($value)) {
            return true;
        }

        $this->_error(self::INSECURE);
        return false;
    }
}