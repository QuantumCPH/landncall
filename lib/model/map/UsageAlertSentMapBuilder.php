<?php


/**
 * This class adds structure of 'usage_alert_sent' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/03/12 10:31:42
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class UsageAlertSentMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.UsageAlertSentMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(UsageAlertSentPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(UsageAlertSentPeer::TABLE_NAME);
		$tMap->setPhpName('UsageAlertSent');
		$tMap->setClassname('UsageAlertSent');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('USAGE_ALERT_ID', 'UsageAlertId', 'INTEGER', true, 11);

		$tMap->addColumn('CUSTOMERID', 'Customerid', 'INTEGER', true, 11);

		$tMap->addColumn('MESSAGETYPE', 'Messagetype', 'VARCHAR', true, 50);

		$tMap->addColumn('ALERT_AMOUNT', 'AlertAmount', 'INTEGER', true, 11);

		$tMap->addColumn('SENTTIME', 'Senttime', 'TIMESTAMP', true, null);

	} // doBuild()

} // UsageAlertSentMapBuilder
