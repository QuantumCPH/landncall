<?php


/**
 * This class adds structure of 'taisys' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 12/27/11 07:27:56
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class TaisysMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.TaisysMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(TaisysPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(TaisysPeer::TABLE_NAME);
		$tMap->setPhpName('Taisys');
		$tMap->setClassname('Taisys');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('SERV', 'Serv', 'VARCHAR', false, 10);

		$tMap->addColumn('IMSI', 'Imsi', 'VARCHAR', false, 15);

		$tMap->addColumn('DN', 'Dn', 'VARCHAR', false, 15);

		$tMap->addColumn('SMSCONTENT', 'Smscontent', 'VARCHAR', false, 255);

		$tMap->addColumn('CHECKSUM', 'Checksum', 'CLOB', false, null);

		$tMap->addColumn('CHECKSUM_VERIFICATION', 'ChecksumVerification', 'BOOLEAN', false, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null);

	} // doBuild()

} // TaisysMapBuilder
