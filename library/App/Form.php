<?php
class App_Form extends Zend_Form
{
    
    protected $_doctrineErrorTemplates = array(
        'notnull' => 'Please choose an option, field cannot be blank.',
        'notblank' => 'Please provide a value, field cannot be blank.',
        'type' => 'Input is wrong type of data.',
        'date' => 'Please make sure value describes a valid date.'
    );
    
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->addElementPrefixPath('App_Filter', 'App/Filter/', 'filter');
        $this->addElementPrefixPath('App_Validate', 'App/Validate/', 'validate');
    }
    
    public function loadDefaultDecorators()
    {
        parent::loadDefaultDecorators();
        $this->addDecorator('Errors', array('placement' => 'PREPEND'));
    }
    
    public function isValidWithDoctrineRecord($data, Doctrine_Record $record)
    {
        $valid = $this->isValid($data);
        $values = $this->getValues();
        $record->fromArray($values);
        
        if(!$record->isValid()){
            $this->addErrorsFromDoctrineErrorStack($record->getErrorStack());
            return false;
        }
        
        return $valid;        
    }
    
    public function addErrorsFromDoctrineException(Doctrine_Validator_Exception $e)
    {
        foreach($e->getInvalidRecords() as $record){
            $this->addErrorsFromDoctrineErrorStack($record->getErrorStack());
        }
    }
    
    public function addErrorsFromDoctrineErrorStack(Doctrine_Validator_ErrorStack $stack)
    {
        foreach($stack as $field => $errors){
            
            $element = $this->getElement($field);

            if($element){
                $value = $element->getValue();
                foreach($errors as $error){
                    $element->addError($this->getDoctrineErrorMessage($error, $value));
                }
            }else{   
                foreach($errors as $error){
                    $this->addError($this->getDoctrineErrorMessage($error, null, $field));
                }
            }
        }
    }
    
    /*
     * @TODO Do something with $value
     */
    protected function getDoctrineErrorMessage($error, $value = null, $field = null)
    {
        if(isset($this->_doctrineErrorTemplates[$error])){
            $error =  $this->_doctrineErrorTemplates[$error];
        }
        
        if(is_string($field)){
            $error = $field . ': ' . $error;
        }
        
        return $error;
    }
    
}