<?php


/**
 * This class adds structure of 'usage_alert' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 12/27/11 12:45:39
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class UsageAlertMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.UsageAlertMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(UsageAlertPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(UsageAlertPeer::TABLE_NAME);
		$tMap->setPhpName('UsageAlert');
		$tMap->setClassname('UsageAlert');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('ALERT_AMOUNT', 'AlertAmount', 'INTEGER', true, 11);

		$tMap->addColumn('SMS_ALERT_MESSAGE', 'SmsAlertMessage', 'LONGVARCHAR', true, null);

		$tMap->addColumn('SMS_ACTIVE', 'SmsActive', 'BOOLEAN', true, null);

		$tMap->addColumn('EMAIL_ALERT_MESSAGE', 'EmailAlertMessage', 'LONGVARCHAR', true, null);

		$tMap->addColumn('EMAIL_ACTIVE', 'EmailActive', 'BOOLEAN', true, null);

		$tMap->addForeignKey('COUNTRY', 'Country', 'INTEGER', 'enable_country', 'ID', true, 11);

		$tMap->addForeignKey('SENDER_NAME', 'SenderName', 'INTEGER', 'usage_alert_sender', 'ID', true, 11);

		$tMap->addForeignKey('STATUS', 'Status', 'INTEGER', 'status', 'ID', true, 11);

	} // doBuild()

} // UsageAlertMapBuilder
