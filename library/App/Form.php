<?php
class App_Form extends Zend_Form
{
    
    protected $_doctrineErrorTemplates = array(
        'notnull' => 'Please choose an option, field cannot be blank.',
        'notblank' => 'Please provide a value, field cannot be blank.',
        'type' => 'Input is wrong type of data.',
        'date' => 'Please make sure value describes a valid date.',
        'email' => 'Please make sure value is a valid email address.',
        'regexp' => 'Invalid format.'
    );
    
    protected $_elementDecorators = array(
        'ViewHelper',
        array('Description', array('tag' => 'p', 'class' => 'description')),
        'FormElementErrors',
        array('HtmlTag', array('tag' => 'dd')),
        array('Label', array('tag' => 'dt', 'requiredSuffix' => ' *'))
    );
    
    public function __construct($options = null)
    {
        parent::__construct($options);
        
        $this->addPrefixPath('App_Form_Decorator', 'App/Form/Decorator', 'decorator')
            ->addElementPrefixPath('App_Form_Decorator', 'App/Form/Decorator', 'decorator')
            ->addElementPrefixPath('App_Filter', 'App/Filter/', 'filter')
            ->addElementPrefixPath('App_Validate', 'App/Validate/', 'validate');
    }
    
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('FormElements')
                 ->addDecorator('HtmlTag', array('tag' => 'dl'))
                 ->addDecorator('Form')
                 ->addDecorator('FormErrorsOnly', array('placement' => 'PREPEND'));
        }
        
        foreach($this->getElements() as $el){
            if(!$el instanceof Zend_Form_Element_Submit && substr(get_class($el), 0, 4) != 'App_'){
                $el->setDecorators($this->_elementDecorators);
            }
        }
        
    }
    
    public function isValidWithDoctrineRecord($data, Doctrine_Record $record)
    {
        $valid = $this->isValid($data);
        $values = $this->getValues();
        
        unset($values['id']);
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
                    $element->addError($this->getDoctrineErrorMessage($error, $value, $field));
                }
            }else{   
                foreach($errors as $error){
                    $this->addError($this->getDoctrineErrorMessage($error, null, $field, true));
                }
            }
        }
    }
    
    /*
     * @TODO Do something with $value
     */
    protected function getDoctrineErrorMessage($error, $value = null, $field = null, $appendField = false)
    {
        if(is_string($field)){
            $key = $error . ':' . $field;
            if(!isset($this->_doctrineErrorTemplates[$key])){
                $key = $error;
            }
        }else{
            $key = $error;
        }
        
        if(isset($this->_doctrineErrorTemplates[$key])){
            $error = $this->_doctrineErrorTemplates[$key];
        }
        
        if(is_string($field) && $appendField){
            $error = $field . ': ' . $error;
        }
        
        return $error;
    }
    
}