<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Adduser extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('user', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => 8,
              'autoincrement' => true,
              'primary' => true,
             ),
             'email' => 
             array(
              'type' => 'string',
              'email' => true,
              'length' => 127,
             ),
             'password' => 
             array(
              'type' => 'string',
              'length' => 40,
             ),
             'registration_date' => 
             array(
              'type' => 'timestamp',
              'length' => 25,
             ),
             'activation_code' => 
             array(
              'type' => 'string',
              'length' => 40,
             ),
             'activation_email' => 
             array(
              'type' => 'string',
              'length' => 127,
             ),
             'first_activation_date' => 
             array(
              'type' => 'timestamp',
              'length' => 25,
             ),
             'last_activation_date' => 
             array(
              'type' => 'timestamp',
              'length' => 25,
             ),
             'last_login_date' => 
             array(
              'type' => 'timestamp',
              'length' => 25,
             ),
             'last_login_attempt_date' => 
             array(
              'type' => 'timestamp',
              'length' => 25,
             ),
             'num_login_attempts' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'reset_request_date' => 
             array(
              'type' => 'timestamp',
              'length' => 25,
             ),
             'reset_code' => 
             array(
              'type' => 'string',
              'length' => 40,
             ),
             'first_name' => 
             array(
              'type' => 'string',
              'regexp' => '/^[\\w ]+$/',
              'length' => 63,
             ),
             'last_name' => 
             array(
              'type' => 'string',
              'regexp' => '/^\\w+$/',
              'length' => 63,
             ),
             'member_no' => 
             array(
              'type' => 'string',
              'length' => 63,
             ),
             'avatar' => 
             array(
              'type' => 'string',
              'length' => 32,
             ),
             'signature' => 
             array(
              'type' => 'string',
              'length' => 255,
             ),
             ), array(
             'indexes' => 
             array(
             ),
             'primary' => 
             array(
              0 => 'id',
             ),
             ));
    }

    public function down()
    {
        $this->dropTable('user');
    }
}