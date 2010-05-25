<?php

class App_Validate_DigitCount extends Zend_Validate_Abstract
{
    const EXISTS = 'digitCount';

    protected $_minDigits = 1;

    public function __construct($options)
    {
        $this->_minDigits = $options;
    }

    /**
     * Override invalid message
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::EXISTS => 'Invalid digit count'
    );

    public function isValid($value)
    {
        preg_match_all('/[0-9]/', $value, $matches);

        if (count($matches[0]) >= $this->_minDigits) {
            return true;
        }

        $this->_error(self::EXISTS);
        return false;
    }
}