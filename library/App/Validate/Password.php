<?php

class App_Validate_Password extends Zend_Validate_Abstract
{
    const INSECURE = 'passwordInsecure';
    
    protected $_messageTemplates = array(
    	self::INSECURE => 'Insecure password (must be between %min% and %max% characters long with at least 1 number)'
    );
    protected $_messageVariables = array(
        'min' => '_minChars',
        'max' => '_maxChars'
    );

    protected $_minChars;
    protected $_maxChars;
    
    public function __construct($minChars = 8, $maxChars = 32)
    {
        $this->_minChars = (int)$minChars;
        $this->_maxChars = (int)$maxChars;
    }

    public function isValid($value)
    {
        $validatorChain = new Zend_Validate();
        $validatorChain
        	->addValidator(new Zend_Validate_StringLength($this->_minChars, $this->_maxChars))
        	->addValidator(new Zend_Validate_Regex('/[0-9]/'))
            ->addValidator(new Zend_Validate_Regex('/[a-z]/i'));

        if($validatorChain->isValid($value)){
            return true;
        }

        $this->_error(self::INSECURE);
        return false;
    }
}