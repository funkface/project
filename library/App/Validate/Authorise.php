<?php

class App_Validate_Authorise extends Zend_Validate_Abstract
{
    const NOT_AUTHORISED = 'notAuthorised';

    protected $_messageTemplates = array(
    	self::NOT_AUTHORISED => 'No users with those details exist'
    );
    
    protected $_identityColumn;
    protected $_credentialColumn;
    
    public function __construct($identityColumn = 'username', $credentialColumn = 'password')
    {
        $this->_identityColumn = (string)$identityColumn;
        $this->_credentialColumn = (string)$credentialColumn;
    }

    public function isValid($value, $context = null)
    {
        $value = (string)$value;
        $this->_setValue($value);
        
        if(is_array($context) && !isset($context[$this->_identityColumn])){
        	return false;
        }

        // Get, and setup DB adapter
        $authAdapter = new App_Auth_Adapter_Doctrine();
        $authAdapter->setTableName('Model_User')
                    ->setIdentityColumn($this->_identityColumn)
                    ->setCredentialColumn($this->_credentialColumn)
					->setIdentity($context[$this->_identityColumn])
					->setCredential($value);
					
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);

        // Authorise
        if(!$result->isValid()){
            $this->_error(self::NOT_AUTHORISED);
            return false;
        }

        // Write session
        $data = $authAdapter->getResultRowObject(null, $this->_credentialColumn);
        $auth->getStorage()->write($data);
        
        Zend_Session::regenerateId();
        Zend_Session::rememberMe(Zend_Registry::get('config')->session->length);

        return true;
    }
}