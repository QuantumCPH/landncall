<?php


/**
 * This class adds structure of 'fonet_customer' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 12/27/11 12:45:36
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class FonetCustomerMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.FonetCustomerMapBuilder';

	/**
	 * The database map.
	 */
	private $dbMap;

	/**
	 * Tells us if this DatabaseMapBuilder is built so that we
	 * don't have to re-build it every time.
	 *
	 * @return     boolean true if this DatabaseMapBuilder is built, false otherwise.
	 */
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	/**
	 * Gets the databasemap this map builder built.
	 *
	 * @return     the databasemap
	 */
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	/**
	 * The doBuild() method builds the DatabaseMap
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap(FonetCustomerPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(FonetCustomerPeer::TABLE_NAME);
		$tMap->setPhpName('FonetCustomer');
		$tMap->setClassname('FonetCustomer');

		$tMap->setUseIdGenerator(false);

		$tMap->addPrimaryKey('FONET_CUSTOMER_ID', 'FonetCustomerId', 'DOUBLE', true, null);

		$tMap->addColumn('FONET_CUSTOMER_CODE', 'FonetCustomerCode', 'VARCHAR', true, 255);

		$tMap->addColumn('ACTIVATED_ON', 'ActivatedOn', 'TIMESTAMP', false, null);

		$tMap->addColumn('SIP_NAME', 'SipName', 'VARCHAR', false, 255);

		$tMap->addColumn('SIP_PWD', 'SipPwd', 'VARCHAR', false, 255);

	} // doBuild()

} // FonetCustomerMapBuilder
