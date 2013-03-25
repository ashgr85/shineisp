<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('CustomersDomainsRegistrars', 'doctrine');

/**
 * BaseCustomersDomainsRegistrars
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $nic_id
 * @property integer $customer_id
 * @property integer $domain_id
 * @property integer $registrars_id
 * @property date $creationdate
 * @property string $value
 * @property Customers $Customers
 * @property Registrars $Registrars
 * @property Domains $Domains
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCustomersDomainsRegistrars extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('customers_domains_registrars');
        $this->hasColumn('nic_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('customer_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => '4',
             ));
        $this->hasColumn('domain_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => '4',
             ));
        $this->hasColumn('registrars_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => '4',
             ));
        $this->hasColumn('creationdate', 'date', 25, array(
             'type' => 'date',
             'notnull' => true,
             'length' => '25',
             ));
        $this->hasColumn('value', 'string', 100, array(
             'type' => 'string',
             'notnull' => false,
             'length' => '100',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Customers', array(
             'local' => 'customer_id',
             'foreign' => 'customer_id'));

        $this->hasOne('Registrars', array(
             'local' => 'registrars_id',
             'foreign' => 'registrars_id'));

        $this->hasOne('Domains', array(
             'local' => 'domain_id',
             'foreign' => 'domain_id'));
    }
}