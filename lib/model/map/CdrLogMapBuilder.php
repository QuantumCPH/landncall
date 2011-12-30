<?php


/**
 * This class adds structure of 'cdr_log' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 12/30/11 11:22:04
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class CdrLogMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CdrLogMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(CdrLogPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CdrLogPeer::TABLE_NAME);
		$tMap->setPhpName('CdrLog');
		$tMap->setClassname('CdrLog');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('DATE', 'Date', 'TIMESTAMP', true, null);

		$tMap->addColumn('FROM_LAND', 'FromLand', 'VARCHAR', true, 100);

		$tMap->addColumn('FROM_NO', 'FromNo', 'VARCHAR', true, 50);

		$tMap->addForeignKey('TO_DESTINATION_RATE_ID', 'ToDestinationRateId', 'INTEGER', 'destination_rate', 'ID', false, 11);

		$tMap->addForeignKey('FROM_EMPLOYEE_ID', 'FromEmployeeId', 'INTEGER', 'employee', 'ID', false, 11);

		$tMap->addColumn('TO_NO', 'ToNo', 'VARCHAR', true, 50);

		$tMap->addColumn('DUR_HMS', 'DurHms', 'VARCHAR', true, 10);

		$tMap->addColumn('DUR_SECS', 'DurSecs', 'BIGINT', true, 20);

		$tMap->addColumn('PRICE', 'Price', 'FLOAT', true, null);

		$tMap->addColumn('PURCHASE_PRICE', 'PurchasePrice', 'FLOAT', false, null);

		$tMap->addColumn('SALE_PRICE', 'SalePrice', 'FLOAT', false, null);

		$tMap->addColumn('DESCRIPTION', 'Description', 'VARCHAR', true, 200);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', true, null);

	} // doBuild()

} // CdrLogMapBuilder
