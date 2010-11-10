<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Addmessage extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('message', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => 8,
              'autoincrement' => true,
              'primary' => true,
             ),
             'from_user_id' => 
             array(
              'type' => 'integer',
              'notnull' => true,
              'length' => 8,
             ),
             'created_date' => 
             array(
              'type' => 'timestamp',
              'length' => 25,
             ),
             'sent_date' => 
             array(
              'type' => 'timestamp',
              'length' => 25,
             ),
             'type' => 
             array(
              'type' => 'enum',
              'values' => 
              array(
              0 => 'message',
              1 => 'request',
              2 => 'report',
              ),
              'default' => 'message',
              'notnull' => true,
              'length' => NULL,
             ),
             'subject' => 
             array(
              'type' => 'string',
              'notblank' => true,
              'length' => 63,
             ),
             'body' => 
             array(
              'type' => 'string',
              'length' => 102400,
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
        $this->dropTable('message');
    }
}