<?php


/**
 * This class adds structure of 'billing_cdr_details' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 12/27/11 07:27:51
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class BillingCdrDetailsMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.BillingCdrDetailsMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(BillingCdrDetailsPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(BillingCdrDetailsPeer::TABLE_NAME);
		$tMap->setPhpName('BillingCdrDetails');
		$tMap->setClassname('BillingCdrDetails');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('CALL_TIME', 'CallTime', 'TIMESTAMP', true, null);

		$tMap->addColumn('FROM_COUNTRY', 'FromCountry', 'VARCHAR', true, 255);

		$tMap->addColumn('FROM_NUMBER', 'FromNumber', 'VARCHAR', true, 255);

		$tMap->addColumn('TO_NUMBER', 'ToNumber', 'VARCHAR', true, 255);

		$tMap->addColumn('DURATION', 'Duration', 'VARCHAR', true, 255);

		$tMap->addColumn('DURATION_SECOND', 'DurationSecond', 'INTEGER', true, 255);

		$tMap->addColumn('PURCHASE_PRICE', 'PurchasePrice', 'INTEGER', true, 11);

		$tMap->addColumn('FONET_DESCRIPTION', 'FonetDescription', 'VARCHAR', true, 255);

		$tMap->addColumn('BILLING_STATUS', 'BillingStatus', 'INTEGER', true, 11);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', true, null);

		$tMap->addColumn('DURATION_MINUTES', 'DurationMinutes', 'INTEGER', false, 11);

		$tMap->addColumn('EMPLOYEE_ID', 'EmployeeId', 'INTEGER', false, 11);

		$tMap->addColumn('CALL_RATE_TABLE_DESCRIPTION', 'CallRateTableDescription', 'VARCHAR', false, 255);

		$tMap->addColumn('CALL_RATE_TABLE_ID', 'CallRateTableId', 'INTEGER', false, 11);

		$tMap->addColumn('PROCESSING_COMMENT', 'ProcessingComment', 'VARCHAR', false, 255);

	} // doBuild()

} // BillingCdrDetailsMapBuilder
