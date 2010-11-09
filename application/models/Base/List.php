<?php

/**
 * Model_Base_List
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $project_id
 * @property integer $milestone_id
 * @property integer $created_user_id
 * @property timestamp $created_date
 * @property string $title
 * @property Model_User $Creator
 * @property Model_Project $Project
 * @property Model_Milestone $Milestone
 * @property Doctrine_Collection $Todo
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_List extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('list');
        $this->hasColumn('project_id', 'integer', 8, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 8,
             ));
        $this->hasColumn('milestone_id', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('created_user_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('created_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('title', 'string', 127, array(
             'type' => 'string',
             'length' => '127',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Model_User as Creator', array(
             'local' => 'created_user_id',
             'foreign' => 'id'));

        $this->hasOne('Model_Project as Project', array(
             'local' => 'project_id',
             'foreign' => 'id'));

        $this->hasOne('Model_Milestone as Milestone', array(
             'local' => 'milestone_id',
             'foreign' => 'id'));

        $this->hasMany('Model_Todo as Todo', array(
             'local' => 'id',
             'foreign' => 'list_id'));
    }
}