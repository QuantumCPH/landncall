<?php


/**
 * This class adds structure of 'package' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 12/27/11 12:45:37
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class PackageMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.PackageMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(PackagePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(PackagePeer::TABLE_NAME);
		$tMap->setPhpName('Package');
		$tMap->setClassname('Package');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('NAME', 'Name', 'VARCHAR', false, 50);

		$tMap->addColumn('BILLING_DUR', 'BillingDur', 'INTEGER', true, 11);

		$tMap->addColumn('BILLING_DUE_DAYS', 'BillingDueDays', 'TINYINT', false, 4);

		$tMap->addColumn('SPECIFICATOIN_COST', 'SpecificatoinCost', 'FLOAT', false, null);

		$tMap->addColumn('R1_COST', 'R1Cost', 'FLOAT', false, null);

		$tMap->addColumn('R2_COST', 'R2Cost', 'FLOAT', false, null);

		$tMap->addColumn('ACTIVATON_COST', 'ActivatonCost', 'FLOAT', false, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', true, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null);

	} // doBuild()

} // PackageMapBuilder
