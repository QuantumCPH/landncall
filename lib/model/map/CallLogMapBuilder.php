<?php


/**
 * This class adds structure of 'call_log' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 12/27/11 12:45:34
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class CallLogMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CallLogMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(CallLogPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CallLogPeer::TABLE_NAME);
		$tMap->setPhpName('CallLog');
		$tMap->setClassname('CallLog');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('IMSI', 'Imsi', 'VARCHAR', true, 255);

		$tMap->addColumn('DEST', 'Dest', 'INTEGER', true, 20);

		$tMap->addColumn('MAC', 'Mac', 'INTEGER', true, 20);

		$tMap->addColumn('MOBILE_NUMBER', 'MobileNumber', 'INTEGER', true, 20);

		$tMap->addColumn('CREATED', 'Created', 'TIMESTAMP', true, null);

		$tMap->addColumn('CHECK_STATUS', 'CheckStatus', 'BOOLEAN', true, null);

	} // doBuild()

} // CallLogMapBuilder
