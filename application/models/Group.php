<?php

/**
 * Model_Group
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Model_Group extends Model_Base_Group
{

    public function requestMembership(Model_User $user)
    {        
        $leaders = $this->findLeaders();
        
        if($leaders->count() > 0){
            
            $message = new Model_Message();
            $message->To = $leaders;
            $message->From = $user;
            $message->subject = $this->abbr . ' Group membership request';
            $message->type = 'request';
            $message->save();
        }
    }
    
    public function findLeaders()
    {
        return $this->findUsers('leader');
    }
    
    public function findMembers()
    {
        return $this->findUsers('member');
    }
    
    public function findPendingMembers()
    {
        return $this->findUsers('request');
    }
    
    public function findUsers($role)
    {
        $q = Doctrine_Query::create()
            ->from('User u')
            ->innerJoin('UserGroup g')
            ->where('g.Group = ', $this);
            
        if($role){
            $q->andWhere('g.role = ', $role);
        }
            
        return $q->execute();
    }
}