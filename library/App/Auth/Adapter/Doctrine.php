<?php

class App_Auth_Adapter_Doctrine implements Zend_Auth_Adapter_Interface
{

    /**
     * $_tableName - the table name to check
     *
     * @var string
     */
    protected $_tableName = null;

    /**
     * $_identityColumn - the column to use as the identity
     *
     * @var string
     */
    protected $_identityColumn = null;

    /**
     * $_credentialColumns - columns to be used as the credentials
     *
     * @var string
     */
    protected $_credentialColumn = null;

    /**
     * $_identity - Identity value
     *
     * @var string
     */
    protected $_identity = null;

    /**
     * $_credential - Credential values
     *
     * @var string
     */
    protected $_credential = null;

   	/**
     * $_resultRow - Results of database authentication query
     *
     * @var array
     */
    protected $_resultRow = null;
    
    /**
     * $_identityRecords - All records with _identity = _identityColumn
     *
     * @var Doctrine_Collection
     */
    protected $_identityRecords = null;

    /**
     * setTableName() - set the table name to be used in the select query
     *
     * @param  string $tableName
     * @return App_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;
        return $this;
    }

    /**
     * setIdentityColumn() - set the column name to be used as the identity column
     *
     * @param  string $identityColumn
     * @return App_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setIdentityColumn($identityColumn)
    {
        $this->_identityColumn = $identityColumn;
        return $this;
    }

    /**
     * setCredentialColumn() - set the column name to be used as the credential column
     *
     * @param  string $credentialColumn
     * @return App_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setCredentialColumn($credentialColumn)
    {
        $this->_credentialColumn = $credentialColumn;
        return $this;
    }

    /**
     * setIdentity() - set the value to be used as the identity
     *
     * @param  string $value
     * @return App_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setIdentity($value)
    {
        $this->_identity = $value;
        return $this;
    }

    /**
     * setCredential() - set the credential value to be used, optionally can specify a treatment
     * to be used, should be supplied in parameterized form, such as 'MD5(?)' or 'PASSWORD(?)'
     *
     * @param  string $credential
     * @return App_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setCredential($credential)
    {
    	$filter = new App_Filter_EncryptSha1();
        $this->_credential = $filter->filter($credential);
        return $this;
    }

    /**
     * authenticate() - defined by Zend_Auth_Adapter_Interface.  This method is called to
     * attempt an authentication.  Previous to this call, this adapter would have already
     * been configured with all necessary information to successfully connect to a database
     * table and attempt to find a record matching the provided identity.
     *
     * @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        $exception = null;
        $this->_resultRow = null;
        $this->_identityRecords = null;

        if ($this->_tableName == '') {
            $exception = 'A table must be supplied for the App_Auth_Adapter_Doctrine authentication adapter.';
        } elseif ($this->_identityColumn == '') {
            $exception = 'An identity column must be supplied for the App_Auth_Adapter_Doctrine authentication adapter.';
        } elseif ($this->_credentialColumn == '') {
            $exception = 'A credential column must be supplied for the App_Auth_Adapter_Doctrine authentication adapter.';
        } elseif ($this->_identity == '') {
            $exception = 'A value for the identity was not provided prior to authentication with App_Auth_Adapter_Doctrine.';
        } elseif ($this->_credential === null) {
            $exception = 'A credential value was not provided prior to authentication with App_Auth_Adapter_Doctrine.';
        }

        if (null !== $exception) {
            throw new Zend_Auth_Adapter_Exception($exception);
        }
    	
        $table = Doctrine_Core::getTable($this->_tableName);
        $q = $table->createQuery('u');
        $q->where('u.' . $this->_identityColumn . ' = ?', $this->_identity);
        	//->andWhere('u.' . $this->_credentialColumn . ' = ?', $this->_credential);
        	
       	//$results = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
       	$this->_identityRecords = $q->execute();
       	
       	switch(count($this->_identityRecords)){
       		
       		case 0:
       			$code = Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
            	$messages[] = 'A record with the supplied identity could not be found.';
       			break;
       			
       		case 1:
       		    if($this->_identityRecords[0]->{$this->_credentialColumn} == $this->_credential){
           			$code = Zend_Auth_Result::SUCCESS;
            		$messages[] = 'Authentication successful.';
            		$this->_resultRow = $this->_identityRecords[0]->toArray();
       		    }else{
       		        $code = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
                    $messages[] = 'Supplied credential is invalid.';
       		    }
        		break;
        		
       		default:
	       		$code = Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS;
	       		$messages[] = 'More than one record matches the supplied identity.';
	       		break;
       	}
        
        return new Zend_Auth_Result($code, $this->_identity, $messages);
    }
    
    /**
     * getIdentityRecords() - Returns all records that matched identity
     * 
     * @return Doctrine_Collection|null
     */
    public function getIdentityRecords()
    {
        return $this->_identityRecords;
    }
    
	/**
     * getResultRowObject() - Returns the result row as a stdClass object
     *
     * @param  string|array $returnColumns
     * @param  string|array $omitColumns
     * @return stdClass|boolean
     */
    public function getResultRowObject($returnColumns = null, $omitColumns = null)
    {
        if (!$this->_resultRow) {
            return false;
        }

        $returnObject = new stdClass();

        if (null !== $returnColumns) {

            $availableColumns = array_keys($this->_resultRow);
            foreach ( (array) $returnColumns as $returnColumn) {
                if (in_array($returnColumn, $availableColumns)) {
                    $returnObject->{$returnColumn} = $this->_resultRow[$returnColumn];
                }
            }
            return $returnObject;

        } elseif (null !== $omitColumns) {

            $omitColumns = (array) $omitColumns;
            foreach ($this->_resultRow as $resultColumn => $resultValue) {
                if (!in_array($resultColumn, $omitColumns)) {
                    $returnObject->{$resultColumn} = $resultValue;
                }
            }
            return $returnObject;

        } else {

            foreach ($this->_resultRow as $resultColumn => $resultValue) {
                $returnObject->{$resultColumn} = $resultValue;
            }
            return $returnObject;

        }
    }	

}
