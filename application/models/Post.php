<?php

/**
 * Model_Post
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Model_Post extends Model_Base_Post
{
    public function preInsert($event)
    {
        $this->created_date = date('c');
        if($this->isFirst()){
            $this->Topic->created_date = $this->created_date;
        }
    }
    
    public function post()
    {
        if($this->posted_date){
            $this->last_edit_date = date('c');
        }else{
            $this->posted_date = date('c');
        }
        $this->save();
    }
    
    public function isFirst()
    {
        $first = $this->getTable()->findFirstByTopic($this->Topic);
        if(!$first){
            return true;
        }
        
        return ($first->id == $this->id);
    }
 
    public function isLast()
    {
        if($this->hasMappedValue('last')){
            return (bool)$this->last;
        }
        
        return false;
    }
    
    public function getTeaser()
    {
        $filter = new App_Filter_TruncateHtmlText();
        return $filter->filter($this->body);
    }
    
    public function getUserCanEdit()
    {
        if($this->hasMappedValue('user_may_edit')){
            return $this->user_may_edit;
        }
        
        return true;
    }
    
    public function softDelete()
    {
        $this->status = 'deleted';
        return $this->save();
    }
    
}