<?php
class App_Form_Element_MultiAutocomplete extends Zend_Form_Element_Textarea
{
    protected $_groups;
    protected $_recipients;
    
    public function init()
    {
        $view = $this->getView();
        
        $view->addScriptPath(realpath(APPLICATION_PATH . '/../library/App/Form/views/scripts/'));
        $view->headScript()
            ->appendFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/jquery-ui.min.js')
            //->appendFile($view->baseUrl('js/xregexp-min.js'))
            //->appendFile($view->baseUrl('js/jcaret-min.js'))
            ->appendFile($view->baseUrl('js/multi-autocomplete-setup.js'));
        
        $this->setAttrib('cols', 30)
            ->setAttrib('rows', 2)
            ->setAttrib('class', 'multi_autocomplete')
            ->addPrefixPath('App_Form_Decorator', 'App/Form/Decorator', 'decorator')
            ->addDecorator('ViewHelperMulti')
            ->addDecorator('Description', array('tag' => 'p', 'class' => 'description'))
            ->addDecorator('Errors')
            ->addDecorator('HtmlTag', array('tag' => 'dd', 'id'  => $this->getName() . '-element'))
            ->addDecorator('Label', array('tag' => 'dt', 'requiredSuffix' => ' *'))
            ->addFilter('ListToArray')
            ->addValidator('UserFullNameMulti', true, array($this->_groups, $this->_recipients));
    }
    
    public function setGroups(Doctrine_Collection $groups)
    {
        $this->_groups = $groups;
        return $this;
    }
    
    public function setRecipients(Doctrine_Collection $recipients)
    {
        $this->_recipients = $recipients;
        
        $value = array();
        foreach($recipients as $recipient){
            $value[] = $recipient->fullName;
        }
        $this->setValue($value);
        
        return $this;
    }
    
}