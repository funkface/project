<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version13 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->removeColumn('user', 'activation_date');
        $this->addColumn('user', 'activation_email', 'string', '127', array(
             ));
        $this->addColumn('user', 'first_activation_date', 'timestamp', '25', array(
             ));
        $this->addColumn('user', 'last_activation_date', 'timestamp', '25', array(
             ));
    }

    public function down()
    {
        $this->addColumn('user', 'activation_date', 'timestamp', '25', array(
             ));
        $this->removeColumn('user', 'activation_email');
        $this->removeColumn('user', 'first_activation_date');
        $this->removeColumn('user', 'last_activation_date');
    }
}