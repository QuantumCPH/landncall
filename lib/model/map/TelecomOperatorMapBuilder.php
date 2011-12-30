<?php


/**
 * This class adds structure of 'telecom_operator' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 12/30/11 11:22:08
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class TelecomOperatorMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.TelecomOperatorMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(TelecomOperatorPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(TelecomOperatorPeer::TABLE_NAME);
		$tMap->setPhpName('TelecomOperator');
		$tMap->setClassname('TelecomOperator');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('NAME', 'Name', 'VARCHAR', false, 50);

		$tMap->addForeignKey('STATUS_ID', 'StatusId', 'INTEGER', 'status', 'ID', true, 11);

		$tMap->addForeignKey('COUNTRY_ID', 'CountryId', 'INTEGER', 'enable_country', 'ID', true, 11);

	} // doBuild()

} // TelecomOperatorMapBuilder
