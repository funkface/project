<?php
class App_View_Control_Grid extends App_View_Control_Abstract
{
    protected $_itemsPerPage = 20;
    protected $_currentPage = 1;
    protected $_numPageLinks = 9;
    protected $_sortBy;
    protected $_sortableColumns = array();
    protected $_query;
    protected $_urlCallback;
    
    protected $_descending = false;
    protected $_sortableColumnNames = array();
    
    protected $_defaultViewScript = '_grid.phtml';

    public function setItemsPerPage($numItems)
    {
        $this->_itemsPerPage = (int)$numItems;
        return $this;
    }
    
    public function setCurrentPage($currentPage)
    {
        $this->_currentPage = max(1, (int)$currentPage);
        return $this;
    }
    
    public function setNumPageLinks($numPageLinks)
    {
        $this->_numPageLinks = (int)$numPageLinks;
        return $this;
    }
    
    public function setSortBy($sort)
    {
        $sort = (string)$sort;
        if($this->_descending = ($sort[0] == '-')){
            $sort = substr($sort, 1);
        }
        $this->_sortBy = $sort;
        return $this;
    }
    
    public function setSortableColumns(array $columns)
    {
        $this->_sortableColumns = $columns;
        $this->_sortableColumnNames = array_keys($columns);
        return $this;
    }
    
    public function setQuery(Doctrine_Query $query)
    {
        $this->_query = $query;
        return $this;
    }
    
    public function setUrlCallback($callback)
    {
        $this->_urlCallback = $callback;
        return $this;
    }
    
    public function preRender()
    {
        if($this->_sortBy == null){
            $this->_sortBy = $this->_sortableColumnNames[0];
        }
        
        $orderBy = $this->_sortableColumns[$this->_sortBy] . ($this->_descending ? ' DESC' : ' ASC');
        $this->_query->orderBy($orderBy);
        
        $pager = new Doctrine_Pager($this->_query, $this->_currentPage, $this->itemsPerPage);
        $rows = $pager->execute();
        
        $columns = array();
        foreach($this->_sortableColumns as $column => $db_col){
            
            if($this->_sortBy == $column && !$this->_descending){
                $class = 'desc';
                $colSort = '-' . $column;
            }else{
                $class = 'asc';
                $colSort = $column;
            }
            
            $columns[$column] = array('class' => $class, 'url' => $this->getUrl(
                $this->_currentPage, 
                $this->_itemsPerPage,
                $colSort
            ));
        }
        
        $pagerControls = new Doctrine_Pager_Layout(
            $pager,
            new Doctrine_Pager_Range_Sliding(array('chunk' => $this->_numPageLinks)),
            $this->getUrl('{%page}', $this->_itemsPerPage, $this->_sortBy)
        );
        
        $this->_viewVars = compact('rows', 'columns', 'pagerControls') + $this->_viewVars;
    }
    
    public function getUrl($page, $items, $sort)
    {
        if(empty($this->_urlCallback)){
            $this->setUrlCallback(array($this, 'defaultUrlCallback'));
        }
        
        return call_user_func($this->_urlCallback, $page, $items, $sort);
    }
    
    public function defaultUrlCallback($page, $items, $sort)
    {
        $view = $this->getView();
        return $view->url(compact('page', 'items', 'sort'), null, false, false);
    }
    
}