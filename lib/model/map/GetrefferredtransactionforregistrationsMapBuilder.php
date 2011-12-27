<?php


/**
 * This class adds structure of 'getrefferredtransactionforregistrations' table to 'propel' DatabaseMap object.
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
class GetrefferredtransactionforregistrationsMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.GetrefferredtransactionforregistrationsMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(GetrefferredtransactionforregistrationsPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(GetrefferredtransactionforregistrationsPeer::TABLE_NAME);
		$tMap->setPhpName('Getrefferredtransactionforregistrations');
		$tMap->setClassname('Getrefferredtransactionforregistrations');

		$tMap->setUseIdGenerator(true);

		$tMap->addColumn('AMOUNT', 'Amount', 'DECIMAL', false, 41);

		$tMap->addColumn('AMOUNT_CUR_MONTH', 'AmountCurMonth', 'DECIMAL', false, 41);

		$tMap->addColumn('CUSTOMER_ID', 'CustomerId', 'BIGINT', false, 20);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', true, null);

		$tMap->addColumn('REFERRER_ID', 'ReferrerId', 'INTEGER', false, 11);

		$tMap->addColumn('RE_TODATE', 'ReTodate', 'DOUBLE', false, null);

		$tMap->addColumn('RE_CUR_MONTH', 'ReCurMonth', 'DOUBLE', false, null);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

	} // doBuild()

} // GetrefferredtransactionforregistrationsMapBuilder
