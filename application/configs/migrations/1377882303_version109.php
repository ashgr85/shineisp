<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version109 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->addColumn('tickets', 'order_id', 'int', '4', array(
             ));
    }

    public function down()
    {
        $this->removeColumn('tickets', 'order_id');
    }
}