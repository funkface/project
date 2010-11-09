<?php

/**
 * Model_Base_User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $email
 * @property string $password
 * @property timestamp $registration_date
 * @property string $activation_code
 * @property string $activation_email
 * @property timestamp $first_activation_date
 * @property timestamp $last_activation_date
 * @property timestamp $last_login_date
 * @property timestamp $last_login_attempt_date
 * @property integer $num_login_attempts
 * @property timestamp $reset_request_date
 * @property string $reset_code
 * @property string $first_name
 * @property string $last_name
 * @property string $member_no
 * @property string $avatar
 * @property string $signature
 * @property Doctrine_Collection $Groups
 * @property Doctrine_Collection $ReceivedMessages
 * @property Doctrine_Collection $SentMessages
 * @property Doctrine_Collection $UserGroup
 * @property Doctrine_Collection $UserMessage
 * @property Doctrine_Collection $Topic
 * @property Doctrine_Collection $Post
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('user');
        $this->hasColumn('email', 'string', 127, array(
             'type' => 'string',
             'email' => true,
             'length' => '127',
             ));
        $this->hasColumn('password', 'string', 40, array(
             'type' => 'string',
             'length' => '40',
             ));
        $this->hasColumn('registration_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('activation_code', 'string', 40, array(
             'type' => 'string',
             'length' => '40',
             ));
        $this->hasColumn('activation_email', 'string', 127, array(
             'type' => 'string',
             'length' => '127',
             ));
        $this->hasColumn('first_activation_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('last_activation_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('last_login_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('last_login_attempt_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('num_login_attempts', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('reset_request_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('reset_code', 'string', 40, array(
             'type' => 'string',
             'length' => '40',
             ));
        $this->hasColumn('first_name', 'string', 63, array(
             'type' => 'string',
             'regexp' => '/^[\\w ]+$/',
             'length' => '63',
             ));
        $this->hasColumn('last_name', 'string', 63, array(
             'type' => 'string',
             'regexp' => '/^\\w+$/',
             'length' => '63',
             ));
        $this->hasColumn('member_no', 'string', 63, array(
             'type' => 'string',
             'length' => '63',
             ));
        $this->hasColumn('avatar', 'string', 32, array(
             'type' => 'string',
             'length' => '32',
             ));
        $this->hasColumn('signature', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Model_Group as Groups', array(
             'refClass' => 'Model_UserGroup',
             'local' => 'user_id',
             'foreign' => 'group_id'));

        $this->hasMany('Model_Message as ReceivedMessages', array(
             'refClass' => 'Model_UserMessage',
             'local' => 'user_id',
             'foreign' => 'message_id'));

        $this->hasMany('Model_Message as SentMessages', array(
             'local' => 'id',
             'foreign' => 'from_user_id'));

        $this->hasMany('Model_UserGroup as UserGroup', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Model_UserMessage as UserMessage', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Model_Topic as Topic', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Model_Post as Post', array(
             'local' => 'id',
             'foreign' => 'user_id'));
    }
}