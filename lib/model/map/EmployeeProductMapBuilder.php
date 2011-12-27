<?php


/**
 * This class adds structure of 'employee_product' table to 'propel' DatabaseMap object.
 *
 *
 * This class was autogenerated by Propel 1.3.0-dev on:
 *
 * 12/27/11 07:27:53
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class EmployeeProductMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.EmployeeProductMapBuilder';

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
		$this->dbMap = Propel::getDatabaseMap(EmployeeProductPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(EmployeeProductPeer::TABLE_NAME);
		$tMap->setPhpName('EmployeeProduct');
		$tMap->setClassname('EmployeeProduct');

		$tMap->setUseIdGenerator(false);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11);

		$tMap->addColumn('EMPLOYEE_ID', 'EmployeeId', 'INTEGER', true, 11);

		$tMap->addColumn('PRODUCT_ID', 'ProductId', 'INTEGER', true, 11);

		$tMap->addColumn('QUANTITY', 'Quantity', 'INTEGER', true, 11);

	} // doBuild()

} // EmployeeProductMapBuilder
