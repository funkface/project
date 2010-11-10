<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Addusergroup extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('user_group', array(
             'user_id' => 
             array(
              'type' => 'integer',
              'primary' => true,
              'length' => 8,
             ),
             'group_id' => 
             array(
              'type' => 'integer',
              'primary' => true,
              'length' => 8,
             ),
             'role' => 
             array(
              'type' => 'enum',
              'values' => 
              array(
              0 => 'request',
              1 => 'member',
              2 => 'leader',
              ),
              'default' => 'request',
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
              1 => 'group_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropTable('user_group');
    }
}