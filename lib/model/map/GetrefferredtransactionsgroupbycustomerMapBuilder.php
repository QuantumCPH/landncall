<?php


/**
 * This class adds structure of 'getrefferredtransactionsgroupbycustomer' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/13/12 05:16:51
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class GetrefferredtransactionsgroupbycustomerMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.GetrefferredtransactionsgroupbycustomerMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(GetrefferredtransactionsgroupbycustomerPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(GetrefferredtransactionsgroupbycustomerPeer::TABLE_NAME);
		$tMap->setPhpName('Getrefferredtransactionsgroupbycustomer');
		$tMap->setClassname('Getrefferredtransactionsgroupbycustomer');

		$tMap->setUseIdGenerator(true);

		$tMap->addColumn('CUSTOMER_ID', 'CustomerId', 'BIGINT', false, 20);

		$tMap->addColumn('IS_FIRST_ORDER', 'IsFirstOrder', 'BOOLEAN', false, null);

		$tMap->addColumn('AMOUNT', 'Amount', 'DECIMAL', false, 41);

		$tMap->addColumn('AMOUNT_CUR_MONTH', 'AmountCurMonth', 'DECIMAL', false, 41);

		$tMap->addColumn('REFERRER_ID', 'ReferrerId', 'INTEGER', false, 11);

		$tMap->addColumn('RE_TODATE', 'ReTodate', 'DOUBLE', false, null);

		$tMap->addColumn('RE_CUR_MONTH', 'ReCurMonth', 'DOUBLE', false, null);

		$tMap->addColumn('ERE_TODATE', 'EreTodate', 'DOUBLE', false, null);

		$tMap->addColumn('ERE_CUR_MONTH', 'EreCurMonth', 'DOUBLE', false, null);

		$tMap->addColumn('TOTAL_TRANSACTIONS', 'TotalTransactions', 'BIGINT', false, 21);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

	} // doBuild()

} // GetrefferredtransactionsgroupbycustomerMapBuilder
