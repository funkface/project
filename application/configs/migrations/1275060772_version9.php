<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version9 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('user', 'first_name', 'string', '63', array(
             ));
        $this->addColumn('user', 'last_name', 'string', '63', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('user', 'first_name');
        $this->removeColumn('user', 'last_name');
    }
}