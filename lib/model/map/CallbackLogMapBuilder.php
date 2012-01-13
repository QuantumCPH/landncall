<?php


/**
 * This class adds structure of 'callback_log' table to 'propel' DatabaseMap object.
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
class CallbackLogMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CallbackLogMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(CallbackLogPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CallbackLogPeer::TABLE_NAME);
		$tMap->setPhpName('CallbackLog');
		$tMap->setClassname('CallbackLog');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 25);

		$tMap->addColumn('MOBILE_NUMBER', 'MobileNumber', 'VARCHAR', true, 255);

		$tMap->addColumn('CALLINGCODE', 'Callingcode', 'INTEGER', true, 11);

		$tMap->addColumn('UNIQUEID', 'Uniqueid', 'VARCHAR', true, 255);

		$tMap->addColumn('IMSI', 'Imsi', 'VARCHAR', true, 250);

		$tMap->addColumn('CREATED', 'Created', 'TIMESTAMP', true, null);

		$tMap->addColumn('CHECK_STATUS', 'CheckStatus', 'TINYINT', true, 4);

	} // doBuild()

} // CallbackLogMapBuilder
