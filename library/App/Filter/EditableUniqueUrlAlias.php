<?php
class App_Filter_EditableUniqueUrlAlias implements Zend_Filter_Interface
{
    
    protected $_titleValue;
    protected $_sectionId;
    protected $_pageId;
    
    public function __construct(Zend_Form_Element $titleElement, Zend_Form_Element $idElement, $sectionId)
    {
        $this->_titleValue = $titleElement->getValue();
        $this->_pageId = $idElement->getValue();
        $this->_sectionId = $sectionId;
    }
    
    public function filter($value)
    {
        $value = trim($value);
        
        if(empty($value)){
            $value = $this->_titleValue;
        }
        
        $pageTable = new Model_Table_Page;
        return $pageTable->getAliasFromTitle($this->_sectionId, $value, $this->_pageId);
    }
}
?>