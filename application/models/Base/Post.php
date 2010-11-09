<?php

/**
 * Model_Base_Post
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $topic_id
 * @property integer $user_id
 * @property timestamp $created_date
 * @property timstamp $posted_date
 * @property timestamp $last_edit_date
 * @property enum $status
 * @property string $body
 * @property Doctrine_Collection $Images
 * @property Model_Topic $Topic
 * @property Model_User $User
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_Post extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('post');
        $this->hasColumn('topic_id', 'integer', 8, array(
             'type' => 'integer',
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
        $this->hasColumn('posted_date', 'timstamp', null, array(
             'type' => 'timstamp',
             ));
        $this->hasColumn('last_edit_date', 'timestamp', null, array(
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
        $this->hasColumn('body', 'string', 102400, array(
             'type' => 'string',
             'length' => '102400',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Model_PostImage as Images', array(
             'local' => 'id',
             'foreign' => 'post_id'));

        $this->hasOne('Model_Topic as Topic', array(
             'local' => 'topic_id',
             'foreign' => 'id'));

        $this->hasOne('Model_User as User', array(
             'local' => 'user_id',
             'foreign' => 'id'));
    }
}