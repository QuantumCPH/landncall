<?php


/**
 * This class adds structure of 'call_history' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 12/30/11 11:22:03
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class CallHistoryMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CallHistoryMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(CallHistoryPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CallHistoryPeer::TABLE_NAME);
		$tMap->setPhpName('CallHistory');
		$tMap->setClassname('CallHistory');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addColumn('MOBILE_NUMBER', 'MobileNumber', 'VARCHAR', true, 255);

		$tMap->addColumn('CALL_DATE', 'CallDate', 'TIMESTAMP', false, null);

		$tMap->addColumn('CALL_DURATION', 'CallDuration', 'VARCHAR', false, 13);

		$tMap->addColumn('DESTINATION', 'Destination', 'VARCHAR', false, 255);

		$tMap->addColumn('USER_CHARGE', 'UserCharge', 'VARCHAR', false, 255);

		$tMap->addColumn('VENDOR', 'Vendor', 'VARCHAR', true, 5);

	} // doBuild()

} // CallHistoryMapBuilder
