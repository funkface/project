<?php

class App_Validate_Authorise extends Zend_Validate_Abstract
{
    const NOT_AUTHORISED = 'notAuthorised';

    protected $_messageTemplates = array(
    	self::NOT_AUTHORISED => 'No users with those details exist'
    );

    public function isValid($value, $context = null)
    {
        $value = (string)$value;
        $this->_setValue($value);
        
        if(is_array($context) && !isset($context['password'])){
        	return false;
        }

        // Get, and setup DB adapter
        $authAdapter = new App_Auth_Adapter_Doctrine();
        $authAdapter->setTableName('Model_User')
                    ->setIdentityColumn('email')
                    ->setCredentialColumn('password')
					->setIdentity($value)
					->setCredential($context['password']);
					
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);

        // Authorise
        if (!$result->isValid()) {
            $this->_error(self::NOT_AUTHORISED);
            return false;
        }

        // Write session
        $data = $authAdapter->getResultRowObject(null, 'password');
        $auth->getStorage()->write($data);
        
        Zend_Session::regenerateId();
        Zend_Session::rememberMe(Zend_Registry::get('config')->session->length);

        return true;
    }
}