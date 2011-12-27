<?php


/**
 * This class adds structure of 'zerocall_cdr' table to 'propel' DatabaseMap object.
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
class ZerocallCdrMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.ZerocallCdrMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(ZerocallCdrPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(ZerocallCdrPeer::TABLE_NAME);
		$tMap->setPhpName('ZerocallCdr');
		$tMap->setClassname('ZerocallCdr');

		$tMap->setUseIdGenerator(false);

		$tMap->addPrimaryKey('CDRKEY', 'Cdrkey', 'VARCHAR', true, 255);

		$tMap->addColumn('CUSTOMID', 'Customid', 'VARCHAR', false, 255);

		$tMap->addColumn('ANSWERTIMEB', 'Answertimeb', 'VARCHAR', false, 255);

		$tMap->addColumn('ENDTIMEB', 'Endtimeb', 'VARCHAR', false, 255);

		$tMap->addColumn('BILLSEC', 'Billsec', 'VARCHAR', false, 255);

		$tMap->addColumn('BILLINGTIME', 'Billingtime', 'VARCHAR', false, 255);

		$tMap->addColumn('EXTENSION', 'Extension', 'VARCHAR', false, 255);

		$tMap->addColumn('SOURCECTY', 'Sourcecty', 'VARCHAR', false, 255);

		$tMap->addColumn('ANI', 'Ani', 'VARCHAR', false, 255);

		$tMap->addColumn('DESTCTY', 'Destcty', 'VARCHAR', false, 255);

		$tMap->addColumn('ROUNDING', 'Rounding', 'VARCHAR', false, 255);

		$tMap->addColumn('USEDVALUE', 'Usedvalue', 'VARCHAR', false, 255);

		$tMap->addColumn('INITIALACCOUNT', 'Initialaccount', 'VARCHAR', false, 255);

		$tMap->addColumn('DST_CUSTOMID', 'DstCustomid', 'VARCHAR', false, 255);

		$tMap->addColumn('DESTINATIONNAME', 'Destinationname', 'VARCHAR', false, 255);

		$tMap->addColumn('COST_RATEMATCHPHNO', 'CostRatematchphno', 'VARCHAR', false, 255);

		$tMap->addColumn('COST_DESTINATIONNAME', 'CostDestinationname', 'VARCHAR', false, 255);

		$tMap->addColumn('COST_RATEVALUE', 'CostRatevalue', 'VARCHAR', false, 255);

		$tMap->addColumn('COST_RATEVALUEFIRST', 'CostRatevaluefirst', 'VARCHAR', false, 255);

		$tMap->addColumn('COST_CCSCONNECTCHARGE', 'CostCcsconnectcharge', 'VARCHAR', false, 255);

		$tMap->addColumn('COST_USEDVALUE', 'CostUsedvalue', 'VARCHAR', false, 255);

		$tMap->addColumn('BZ2_RATE1MINUTE', 'Bz2Rate1minute', 'VARCHAR', false, 255);

		$tMap->addColumn('BZ1_RATEADDMINUTE', 'Bz1Rateaddminute', 'VARCHAR', false, 255);

		$tMap->addColumn('EXECUTE_STATUS', 'ExecuteStatus', 'BOOLEAN', false, null);

	} // doBuild()

} // ZerocallCdrMapBuilder
