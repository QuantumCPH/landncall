<?php


/**
 * This class adds structure of 'global_setting' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/03/12 10:31:40
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class GlobalSettingMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.GlobalSettingMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(GlobalSettingPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(GlobalSettingPeer::TABLE_NAME);
		$tMap->setPhpName('GlobalSetting');
		$tMap->setClassname('GlobalSetting');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('NAME', 'Name', 'VARCHAR', true, 100);

		$tMap->addColumn('VALUE', 'Value', 'VARCHAR', false, 999);

		$tMap->addColumn('DESCRIPTION', 'Description', 'VARCHAR', false, 999);

	} // doBuild()

} // GlobalSettingMapBuilder
