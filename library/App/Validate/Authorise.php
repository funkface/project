<?php

class App_Validate_Authorise extends Zend_Validate_Abstract
{
    const NOT_AUTHORISED = 'notAuthorised';

    protected $_messageTemplates = array(
    	self::NOT_AUTHORISED => 'No users with those details exist'
    );
    
    protected $_tableName;
    protected $_identityColumn;
    protected $_credentialColumn;
    
    protected $_auth;
    protected $_authAdapter;
    
    public function __construct(
        $tableName = 'Model_User',
        $identityColumn = 'username', 
        $credentialColumn = 'password'
    )
    {
        $this->_tableName = (string)$tableName;
        $this->_identityColumn = (string)$identityColumn;
        $this->_credentialColumn = (string)$credentialColumn;
        
        $this->_authAdapter = new App_Auth_Adapter_Doctrine();
        $this->_authAdapter->setTableName($this->_tableName)
            ->setIdentityColumn($this->_identityColumn)
            ->setCredentialColumn($this->_credentialColumn);
            
        $this->_auth = Zend_Auth::getInstance();;
    }

    public function isValid($value, $context = null)
    {
        $valid = $this->_validate($value, $context);
        
        if($valid){
            $this->_writeSession();
        }
        
        return $valid;
    }
    
    protected function _validate($value, $context)
    {
        $value = (string)$value;
        $this->_setValue($value);
        
        if(is_array($context) && !isset($context[$this->_identityColumn])){
            return false;
        }

        $this->_authAdapter
            ->setIdentity($context[$this->_identityColumn])
            ->setCredential($value);
                    
        $result = $this->_auth->authenticate($this->_authAdapter);

        // Authorise
        if($result->isValid()){
            return true;
        }
        
        $this->_error(self::NOT_AUTHORISED);
        return false;
    }
    
    protected function _writeSession()
    {
        // Write session
        $data = $this->_authAdapter->getResultRowObject(null, $this->_credentialColumn);
        $this->_auth->getStorage()->write($data);
        
        Zend_Session::regenerateId();
        Zend_Session::rememberMe(Zend_Registry::get('config')->session->length);
    }
}