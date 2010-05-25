<?php

/**
 * Model_Base_Milestone
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $project_id
 * @property timestamp $due_date
 * @property integer $created_user_id
 * @property timestamp $created_date
 * @property integer $completed_user_id
 * @property timestamp $completed_date
 * @property string $title
 * @property string $notes
 * @property Model_User $Creator
 * @property Model_User $Completor
 * @property Model_Project $Project
 * @property Doctrine_Collection $List
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_Milestone extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('milestone');
        $this->hasColumn('project_id', 'integer', 8, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 8,
             ));
        $this->hasColumn('due_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('created_user_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('created_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('completed_user_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('completed_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('title', 'string', 127, array(
             'type' => 'string',
             'length' => '127',
             ));
        $this->hasColumn('notes', 'string', 1023, array(
             'type' => 'string',
             'length' => '1023',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Model_User as Creator', array(
             'local' => 'created_user_id',
             'foreign' => 'id'));

        $this->hasOne('Model_User as Completor', array(
             'local' => 'completed_user_id',
             'foreign' => 'id'));

        $this->hasOne('Model_Project as Project', array(
             'local' => 'project_id',
             'foreign' => 'id'));

        $this->hasMany('Model_List as List', array(
             'local' => 'id',
             'foreign' => 'milestone_id'));
    }
}