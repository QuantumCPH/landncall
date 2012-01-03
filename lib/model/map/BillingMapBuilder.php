<?php


/**
 * This class adds structure of 'billing' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/03/12 09:22:01
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class BillingMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.BillingMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(BillingPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(BillingPeer::TABLE_NAME);
		$tMap->setPhpName('Billing');
		$tMap->setClassname('Billing');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('TIME', 'Time', 'TIMESTAMP', true, null);

		$tMap->addColumn('CUSTOMER_ID', 'CustomerId', 'INTEGER', true, 11);

		$tMap->addColumn('MOBILE_NUMBER', 'MobileNumber', 'VARCHAR', true, 20);

		$tMap->addColumn('TO_NUMBER', 'ToNumber', 'VARCHAR', true, 20);

		$tMap->addColumn('DURATION_SECOND', 'DurationSecond', 'VARCHAR', true, 10);

		$tMap->addColumn('DURATION_MINUTES', 'DurationMinutes', 'VARCHAR', true, 10);

		$tMap->addColumn('BILLING_MINUTES', 'BillingMinutes', 'VARCHAR', true, 10);

		$tMap->addColumn('COST_PER_MINUTE', 'CostPerMinute', 'FLOAT', true, null);

		$tMap->addColumn('CALL_COST', 'CallCost', 'FLOAT', true, null);

		$tMap->addColumn('VAT', 'Vat', 'FLOAT', true, null);

		$tMap->addColumn('BALANCE_BEFORE', 'BalanceBefore', 'FLOAT', false, null);

		$tMap->addColumn('BALANCE_AFTER', 'BalanceAfter', 'FLOAT', false, null);

		$tMap->addColumn('RATE_TABLE_DESCRIPTION', 'RateTableDescription', 'VARCHAR', false, 255);

		$tMap->addColumn('RATE_TABLE_ID', 'RateTableId', 'INTEGER', false, 11);

		$tMap->addColumn('BILLING_STATUS', 'BillingStatus', 'INTEGER', false, 11);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null);

		$tMap->addColumn('CDR_ID', 'CdrId', 'INTEGER', false, 11);

	} // doBuild()

} // BillingMapBuilder
