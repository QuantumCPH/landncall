<?php


/**
 * This class adds structure of 'call_rate_table' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/11/12 05:25:21
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class CallRateTableMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CallRateTableMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(CallRateTablePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CallRateTablePeer::TABLE_NAME);
		$tMap->setPhpName('CallRateTable');
		$tMap->setClassname('CallRateTable');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('CALL_RATE_TABLE_ID', 'CallRateTableId', 'INTEGER', true, 11);

		$tMap->addColumn('DESTINATION_NAME', 'DestinationName', 'VARCHAR', true, 255);

		$tMap->addColumn('DESTINATION_NO_FROM', 'DestinationNoFrom', 'VARCHAR', true, 255);

		$tMap->addColumn('CONNECT_CHARGE', 'ConnectCharge', 'VARCHAR', true, 255);

		$tMap->addColumn('RATE', 'Rate', 'VARCHAR', true, 255);

		$tMap->addColumn('RATE_STATUS', 'RateStatus', 'INTEGER', true, 11);

		$tMap->addColumn('RATECREATED', 'Ratecreated', 'TIMESTAMP', true, null);

	} // doBuild()

} // CallRateTableMapBuilder
