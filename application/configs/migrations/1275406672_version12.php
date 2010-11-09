<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version12 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createForeignKey('user', 'user_message_id_message_from_user_id', array(
             'name' => 'user_message_id_message_from_user_id',
             'local' => 'message_id',
             'foreign' => 'from_user_id',
             'foreignTable' => 'message',
             ));
        $this->createForeignKey('user_group', 'user_group_user_id_user_id', array(
             'name' => 'user_group_user_id_user_id',
             'local' => 'user_id',
             'foreign' => 'id',
             'foreignTable' => 'user',
             ));
        $this->createForeignKey('user_group', 'user_group_group_id_group_id', array(
             'name' => 'user_group_group_id_group_id',
             'local' => 'group_id',
             'foreign' => 'id',
             'foreignTable' => 'group',
             ));
        $this->createForeignKey('user_message', 'user_message_user_id_user_id', array(
             'name' => 'user_message_user_id_user_id',
             'local' => 'user_id',
             'foreign' => 'id',
             'foreignTable' => 'user',
             ));
        $this->createForeignKey('user_message', 'user_message_message_id_message_id', array(
             'name' => 'user_message_message_id_message_id',
             'local' => 'message_id',
             'foreign' => 'id',
             'foreignTable' => 'message',
             ));
        $this->addIndex('user', 'user_message_id', array(
             'fields' => 
             array(
              0 => 'message_id',
             ),
             ));
        $this->addIndex('user_group', 'user_group_user_id', array(
             'fields' => 
             array(
              0 => 'user_id',
             ),
             ));
        $this->addIndex('user_group', 'user_group_group_id', array(
             'fields' => 
             array(
              0 => 'group_id',
             ),
             ));
        $this->addIndex('user_message', 'user_message_user_id', array(
             'fields' => 
             array(
              0 => 'user_id',
             ),
             ));
        $this->addIndex('user_message', 'user_message_message_id', array(
             'fields' => 
             array(
              0 => 'message_id',
             ),
             ));
    }

    public function down()
    {
        $this->dropForeignKey('user', 'user_message_id_message_from_user_id');
        $this->dropForeignKey('user_group', 'user_group_user_id_user_id');
        $this->dropForeignKey('user_group', 'user_group_group_id_group_id');
        $this->dropForeignKey('user_message', 'user_message_user_id_user_id');
        $this->dropForeignKey('user_message', 'user_message_message_id_message_id');
        $this->removeIndex('user', 'user_message_id', array(
             'fields' => 
             array(
              0 => 'message_id',
             ),
             ));
        $this->removeIndex('user_group', 'user_group_user_id', array(
             'fields' => 
             array(
              0 => 'user_id',
             ),
             ));
        $this->removeIndex('user_group', 'user_group_group_id', array(
             'fields' => 
             array(
              0 => 'group_id',
             ),
             ));
        $this->removeIndex('user_message', 'user_message_user_id', array(
             'fields' => 
             array(
              0 => 'user_id',
             ),
             ));
        $this->removeIndex('user_message', 'user_message_message_id', array(
             'fields' => 
             array(
              0 => 'message_id',
             ),
             ));
    }
}