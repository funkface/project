<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version7 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('user', 'last_login_date', 'timestamp', '25', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('user', 'last_login_date');
    }
}