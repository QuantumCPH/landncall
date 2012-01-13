<?php


/**
 * This class adds structure of 'cbf' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/13/12 05:16:48
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class CbfMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.CbfMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(CbfPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CbfPeer::TABLE_NAME);
		$tMap->setPhpName('Cbf');
		$tMap->setClassname('Cbf');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('S', 'S', 'VARCHAR', false, 10);

		$tMap->addColumn('DA', 'Da', 'VARCHAR', false, 25);

		$tMap->addColumn('MESSAGE', 'Message', 'VARCHAR', false, 480);

		$tMap->addColumn('ST', 'St', 'INTEGER', false, 11);

		$tMap->addColumn('COUNTRY_ID', 'CountryId', 'INTEGER', false, 11);

		$tMap->addColumn('MOBILE_NUMBER', 'MobileNumber', 'VARCHAR', false, 20);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null);

	} // doBuild()

} // CbfMapBuilder
