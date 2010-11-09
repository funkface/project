<?php

/**
 * Model_Project
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Model_Project extends Model_Base_Project
{

    public function requestMembership(Model_User $user)
    {        
        $leaders = $this->findLeaders();
        
        if($leaders->count() > 0){
            
            $message = new Model_Message();
            $message->From = $user;
            $message->subject = $this->abbr . ' Group membership request';
            $message->type = 'request';
            
            foreach($leaders as $leader){
                $message->To->add($leader);
            }

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
            ->select('u.*')
            ->from('Model_User u')
            ->innerJoin('u.UserGroup g')
            ->where('g.group_id = ?', $this->id);
            
        if($role){
            $q->andWhere('g.role = ?', $role);
        }
            
        return $q->execute();
    }

}