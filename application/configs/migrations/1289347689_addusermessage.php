<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Addusermessage extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('user_message', array(
             'user_id' => 
             array(
              'type' => 'integer',
              'primary' => true,
              'length' => 8,
             ),
             'message_id' => 
             array(
              'type' => 'integer',
              'primary' => true,
              'length' => 8,
             ),
             'status' => 
             array(
              'type' => 'enum',
              'values' => 
              array(
              0 => 'new',
              1 => 'read',
              2 => 'deleted',
              ),
              'default' => 'new',
              'notnull' => true,
              'length' => NULL,
             ),
             ), array(
             'indexes' => 
             array(
             ),
             'primary' => 
             array(
              0 => 'user_id',
              1 => 'message_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropTable('user_message');
    }
}