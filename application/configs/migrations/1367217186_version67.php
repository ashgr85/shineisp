<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version67 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->removeColumn('registrars', 'username');
        $this->removeColumn('registrars', 'password');
        $this->removeColumn('registrars', 'testmode');
        $this->removeColumn('registrars', 'credit');
        $this->addColumn('registrars', 'config', 'string', '', array(
             ));
        $this->changeColumn('registrars', 'class', 'string', '200', array(
             'notnull' => '1',
             ));
        $this->changeColumn('registrars', 'active', 'integer', '1', array(
             ));
    }

    public function down()
    {
        $this->addColumn('registrars', 'username', 'string', '200', array(
             'fixed' => '0',
             'unsigned' => '',
             'primary' => '',
             'notnull' => '1',
             'autoincrement' => '',
             ));
        $this->addColumn('registrars', 'password', 'string', '200', array(
             'fixed' => '0',
             'unsigned' => '',
             'primary' => '',
             'notnull' => '1',
             'autoincrement' => '',
             ));
        $this->addColumn('registrars', 'testmode', 'integer', '1', array(
             'fixed' => '0',
             'default' => '1',
             'unsigned' => '',
             'primary' => '',
             'autoincrement' => '',
             ));
        $this->addColumn('registrars', 'credit', 'integer', '4', array(
             'fixed' => '0',
             'default' => '0',
             'unsigned' => '',
             'primary' => '',
             'autoincrement' => '',
             ));
        $this->removeColumn('registrars', 'config');
    }
}