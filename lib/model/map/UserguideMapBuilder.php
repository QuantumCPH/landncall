<?php


/**
 * This class adds structure of 'userguide' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 01/13/12 05:16:53
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class UserguideMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.UserguideMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(UserguidePeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(UserguidePeer::TABLE_NAME);
		$tMap->setPhpName('Userguide');
		$tMap->setClassname('Userguide');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('TITLE', 'Title', 'VARCHAR', true, 300);

		$tMap->addColumn('DESCRIPTION', 'Description', 'LONGVARCHAR', true, null);

		$tMap->addForeignKey('COUNTRY_ID', 'CountryId', 'INTEGER', 'enable_country', 'ID', false, 11);

		$tMap->addForeignKey('STATUS_ID', 'StatusId', 'INTEGER', 'status', 'ID', false, 11);

		$tMap->addColumn('IMAGE', 'Image', 'VARCHAR', true, 400);

		$tMap->addColumn('CREATE_AT', 'CreateAt', 'TIMESTAMP', false, null);

	} // doBuild()

} // UserguideMapBuilder
