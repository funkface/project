<?php

/**
 * Model_Base_Topic
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $group_id
 * @property integer $user_id
 * @property timestamp $created_date
 * @property enum $status
 * @property boolean $sticky
 * @property string $title
 * @property Model_Group $Group
 * @property Model_User $User
 * @property Doctrine_Collection $Post
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_Topic extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('topic');
        $this->hasColumn('group_id', 'integer', 8, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 8,
             ));
        $this->hasColumn('user_id', 'integer', 8, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 8,
             ));
        $this->hasColumn('created_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('status', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'open',
              1 => 'locked',
              2 => 'deleted',
             ),
             'default' => 'open',
             'notnull' => true,
             ));
        $this->hasColumn('sticky', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'notnull' => true,
             ));
        $this->hasColumn('title', 'string', 63, array(
             'type' => 'string',
             'notblank' => true,
             'length' => '63',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Model_Group as Group', array(
             'local' => 'group_id',
             'foreign' => 'id'));

        $this->hasOne('Model_User as User', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasMany('Model_Post as Post', array(
             'local' => 'id',
             'foreign' => 'topic_id'));
    }
}