<?php

class App_Validate_NotExistsDb extends Zend_Validate_Abstract
{
    const EXISTS = 'exists';

    /**
     * The model class name to query _method against
     * @var string
     */
    protected $_model;

    /**
     * The method name to query from _model. If returns true or > 0, validator fails
     * @var unknown_type
     */
    protected $_method;


    /**
     * Override invalid message
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::EXISTS => 'Already exists'
    );

    /**
     * Construct
     *
     * @param Zend_Db_Model
     * @param String method_name
     */
    public function __construct(Zend_Db_Table $model, $method)
    {

        if (false == $method) {
            throw new Exception('Invalid params for NotExitsDb: array(table=>$table, method=>$method)');
        }

        $this->_model = $model;
        $this->_method = $method;
    }


    public function isValid($value)
    {
        $model = $this->_model;
        $method = $this->_method;

        if ($model->$method($value)) {
            $this->_error(self::EXISTS);
            return false;
        }

        return true;
    }
}